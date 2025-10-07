<?php

namespace App\Services;

use App\Models\Audit;
use App\Models\Recommendation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SeoAuditorService
{
    public function runAudit(Audit $audit): void
    {
        $audit->update(['status' => 'processing']);

        try {
            $crawlData = $this->crawlWebsite($audit->url);
            
            $lighthouseData = $this->getLighthouseScores($audit->url);

            $audit->update([
                'status' => 'completed',
                'metadata' => array_merge($crawlData, $lighthouseData),
                'lighthouse_score_mobile' => $lighthouseData['mobile_score'] ?? null,
                'lighthouse_score_desktop' => $lighthouseData['desktop_score'] ?? null,
                'completed_at' => now(),
            ]);

            $this->generateRecommendations($audit, $crawlData);

        } catch (\Exception $e) {
            $audit->update([
                'status' => 'failed',
                'metadata' => ['error' => $e->getMessage()],
            ]);
            
            throw $e;
        }
    }

    private function crawlWebsite(string $url): array
    {
        $maxRedirects = 5;
        $redirectCount = 0;
        $visitedUrls = [];
        $currentUrl = $url;

        try {
            while ($redirectCount < $maxRedirects) {
                if (in_array($currentUrl, $visitedUrls)) {
                    throw new \Exception("Circular redirect detected");
                }
                
                $visitedUrls[] = $currentUrl;
                
                $validatedIp = $this->validateUrl($currentUrl);
                
                $parsedUrl = parse_url($currentUrl);
                $host = $parsedUrl['host'];
                $port = $parsedUrl['port'] ?? ($parsedUrl['scheme'] === 'https' ? 443 : 80);

                $response = Http::timeout(30)
                    ->withOptions([
                        'allow_redirects' => false,
                        'curl' => [
                            CURLOPT_RESOLVE => ["{$host}:{$port}:{$validatedIp}"],
                        ],
                    ])
                    ->get($currentUrl);

                if ($response->successful()) {
                    $html = $response->body();
                    return $this->extractSeoData($html, $currentUrl);
                }

                if ($response->redirect()) {
                    $location = $response->header('Location');
                    
                    if (!$location) {
                        throw new \Exception("Redirect without Location header");
                    }

                    if (!filter_var($location, FILTER_VALIDATE_URL)) {
                        $location = $this->resolveRelativeUrl($currentUrl, $location);
                    }

                    $currentUrl = $location;
                    $redirectCount++;
                    continue;
                }

                throw new \Exception("Failed to fetch website. HTTP status: " . $response->status());
            }

            throw new \Exception("Too many redirects (max {$maxRedirects})");
            
        } catch (\Exception $e) {
            throw new \Exception("Crawl failed: " . $e->getMessage());
        }
    }

    private function resolveRelativeUrl(string $baseUrl, string $relativeUrl): string
    {
        if (str_starts_with($relativeUrl, '//')) {
            $parsedBase = parse_url($baseUrl);
            return $parsedBase['scheme'] . ':' . $relativeUrl;
        }

        if (str_starts_with($relativeUrl, '/')) {
            $parsedBase = parse_url($baseUrl);
            $scheme = $parsedBase['scheme'];
            $host = $parsedBase['host'];
            $port = isset($parsedBase['port']) ? ':' . $parsedBase['port'] : '';
            return "{$scheme}://{$host}{$port}{$relativeUrl}";
        }

        return rtrim($baseUrl, '/') . '/' . ltrim($relativeUrl, '/');
    }

    /**
     * Validate URL for SSRF protection and return a safe IP to pin the connection
     * 
     * This method provides defense-in-depth against SSRF attacks by:
     * - Restricting protocols to http/https only
     * - Blocking known dangerous hostnames (localhost, cloud metadata endpoints)
     * - Resolving ALL DNS records (A, AAAA, CNAME) and checking every IP
     * - Blocking private/reserved/link-local IP ranges for both IPv4 and IPv6
     * - Returning the validated IP for DNS pinning via CURLOPT_RESOLVE
     * 
     * DNS rebinding protection: The returned IP is pinned in the HTTP request using
     * CURLOPT_RESOLVE, preventing the client from re-resolving the hostname.
     * 
     * @return string The validated public IP address to use for the request
     * @throws \Exception if URL is invalid or resolves to a private/reserved range
     */
    private function validateUrl(string $url): string
    {
        $parsedUrl = parse_url($url);
        
        if (!$parsedUrl || !isset($parsedUrl['scheme']) || !isset($parsedUrl['host'])) {
            throw new \Exception("Invalid URL format");
        }

        if (!in_array(strtolower($parsedUrl['scheme']), ['http', 'https'])) {
            throw new \Exception("Only HTTP and HTTPS protocols are allowed");
        }

        $host = $parsedUrl['host'];

        $blockedHosts = ['localhost', '0.0.0.0', 'metadata.google.internal'];
        if (in_array(strtolower($host), $blockedHosts)) {
            throw new \Exception("Access to this host is not allowed");
        }

        $ips = $this->resolveAllIps($host);

        if (empty($ips)) {
            throw new \Exception("No IP addresses resolved for hostname");
        }

        foreach ($ips as $ip) {
            if ($this->isPrivateOrReservedIp($ip)) {
                throw new \Exception("Access to private or reserved IP ranges is not allowed");
            }
        }

        return $ips[0];
    }

    /**
     * Resolve all IPs for a hostname, following CNAME chains recursively
     * 
     * @param string $host
     * @param array $visited Track visited hostnames to prevent infinite loops
     * @return array All resolved IPv4 and IPv6 addresses
     * @throws \Exception if resolution fails
     */
    private function resolveAllIps(string $host, array $visited = []): array
    {
        if (in_array($host, $visited)) {
            throw new \Exception("Circular CNAME reference detected");
        }

        $visited[] = $host;
        $ips = [];
        
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            return [$host];
        }

        $records = @dns_get_record($host, DNS_A + DNS_AAAA + DNS_CNAME);
        
        if ($records === false || empty($records)) {
            throw new \Exception("Unable to resolve hostname: {$host}");
        }

        foreach ($records as $record) {
            if (isset($record['ip'])) {
                $ips[] = $record['ip'];
            } elseif (isset($record['ipv6'])) {
                $ips[] = $record['ipv6'];
            } elseif (isset($record['target'])) {
                $targetIps = $this->resolveAllIps($record['target'], $visited);
                $ips = array_merge($ips, $targetIps);
            }
        }

        return array_unique($ips);
    }

    private function isPrivateOrReservedIp(string $ip): bool
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                return true;
            }

            if (str_starts_with($ip, '169.254.')) {
                return true;
            }

            return false;
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $ip = strtolower($ip);

            if (str_starts_with($ip, '::ffff:')) {
                $packed = @inet_pton($ip);
                if ($packed !== false && strlen($packed) === 16) {
                    $ipv4 = inet_ntop(substr($packed, 12, 4));
                    if ($ipv4 && filter_var($ipv4, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                        return $this->isPrivateOrReservedIp($ipv4);
                    }
                }
            }

            $privateRanges = [
                'fe80:',
                'fec0:',
                'fc00:',
                'fd00:',
                '::1',
            ];

            foreach ($privateRanges as $range) {
                if (str_starts_with($ip, $range)) {
                    return true;
                }
            }

            return false;
        }

        return true;
    }

    private function extractSeoData(string $html, string $url): array
    {
        $data = [
            'url' => $url,
            'title' => $this->extractTitle($html),
            'meta_description' => $this->extractMetaDescription($html),
            'h1_tags' => $this->extractH1Tags($html),
            'h2_tags' => $this->extractH2Tags($html),
            'canonical_url' => $this->extractCanonical($html),
            'meta_robots' => $this->extractMetaRobots($html),
            'og_tags' => $this->extractOpenGraphTags($html),
            'images_without_alt' => $this->countImagesWithoutAlt($html),
            'internal_links' => $this->countInternalLinks($html, $url),
            'external_links' => $this->countExternalLinks($html, $url),
        ];

        return $data;
    }

    private function extractTitle(string $html): ?string
    {
        preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $matches);
        return $matches[1] ?? null;
    }

    private function extractMetaDescription(string $html): ?string
    {
        preg_match('/<meta\s+name=["\']description["\']\s+content=["\'](.*?)["\']/is', $html, $matches);
        return $matches[1] ?? null;
    }

    private function extractH1Tags(string $html): array
    {
        preg_match_all('/<h1[^>]*>(.*?)<\/h1>/is', $html, $matches);
        return array_map(fn($h1) => strip_tags($h1), $matches[1] ?? []);
    }

    private function extractH2Tags(string $html): array
    {
        preg_match_all('/<h2[^>]*>(.*?)<\/h2>/is', $html, $matches);
        return array_map(fn($h2) => strip_tags($h2), $matches[1] ?? []);
    }

    private function extractCanonical(string $html): ?string
    {
        preg_match('/<link\s+rel=["\']canonical["\']\s+href=["\'](.*?)["\']/is', $html, $matches);
        return $matches[1] ?? null;
    }

    private function extractMetaRobots(string $html): ?string
    {
        preg_match('/<meta\s+name=["\']robots["\']\s+content=["\'](.*?)["\']/is', $html, $matches);
        return $matches[1] ?? null;
    }

    private function extractOpenGraphTags(string $html): array
    {
        $ogTags = [];
        preg_match_all('/<meta\s+property=["\']og:([^"\']+)["\']\s+content=["\'](.*?)["\']/is', $html, $matches);
        
        if (!empty($matches[1])) {
            foreach ($matches[1] as $index => $property) {
                $ogTags[$property] = $matches[2][$index];
            }
        }

        return $ogTags;
    }

    private function countImagesWithoutAlt(string $html): int
    {
        preg_match_all('/<img(?![^>]*alt=)[^>]*>/is', $html, $matches);
        return count($matches[0]);
    }

    private function countInternalLinks(string $html, string $baseUrl): int
    {
        $domain = parse_url($baseUrl, PHP_URL_HOST);
        preg_match_all('/<a\s+href=["\'](.*?)["\']/is', $html, $matches);
        
        $internalCount = 0;
        foreach ($matches[1] ?? [] as $href) {
            if (str_starts_with($href, '/') || str_contains($href, $domain)) {
                $internalCount++;
            }
        }
        
        return $internalCount;
    }

    private function countExternalLinks(string $html, string $baseUrl): int
    {
        $domain = parse_url($baseUrl, PHP_URL_HOST);
        preg_match_all('/<a\s+href=["\'](https?:\/\/.*?)["\']/is', $html, $matches);
        
        $externalCount = 0;
        foreach ($matches[1] ?? [] as $href) {
            if (!str_contains($href, $domain)) {
                $externalCount++;
            }
        }
        
        return $externalCount;
    }

    private function getLighthouseScores(string $url): array
    {
        return [
            'mobile_score' => null,
            'desktop_score' => null,
            'lighthouse_note' => 'Lighthouse integration pending - requires PageSpeed Insights API key',
        ];
    }

    private function generateRecommendations(Audit $audit, array $crawlData): void
    {
        $recommendations = [];

        if (empty($crawlData['title'])) {
            $recommendations[] = [
                'category' => 'seo',
                'priority' => 'fix_first',
                'title' => 'Missing Title Tag',
                'description' => 'Your page is missing a title tag. This is critical for SEO.',
                'how_to_fix' => 'Add a <title> tag in the <head> section with a descriptive, keyword-rich title (50-60 characters).',
                'impact_score' => 10,
                'effort_score' => 2,
            ];
        } elseif (strlen($crawlData['title']) > 60) {
            $recommendations[] = [
                'category' => 'seo',
                'priority' => 'next',
                'title' => 'Title Tag Too Long',
                'description' => 'Your title tag is ' . strlen($crawlData['title']) . ' characters. Google typically displays the first 50-60 characters.',
                'how_to_fix' => 'Shorten your title tag to 50-60 characters while keeping it descriptive.',
                'impact_score' => 7,
                'effort_score' => 2,
            ];
        }

        if (empty($crawlData['meta_description'])) {
            $recommendations[] = [
                'category' => 'seo',
                'priority' => 'fix_first',
                'title' => 'Missing Meta Description',
                'description' => 'Your page is missing a meta description. This affects click-through rates from search results.',
                'how_to_fix' => 'Add a <meta name="description"> tag with a compelling summary of your page (150-160 characters).',
                'impact_score' => 9,
                'effort_score' => 2,
            ];
        } elseif (strlen($crawlData['meta_description']) > 160) {
            $recommendations[] = [
                'category' => 'seo',
                'priority' => 'next',
                'title' => 'Meta Description Too Long',
                'description' => 'Your meta description is ' . strlen($crawlData['meta_description']) . ' characters. Google typically displays 150-160 characters.',
                'how_to_fix' => 'Shorten your meta description to 150-160 characters.',
                'impact_score' => 6,
                'effort_score' => 2,
            ];
        }

        if (count($crawlData['h1_tags']) === 0) {
            $recommendations[] = [
                'category' => 'seo',
                'priority' => 'fix_first',
                'title' => 'Missing H1 Tag',
                'description' => 'Your page has no H1 tag. H1 tags help search engines understand your page content.',
                'how_to_fix' => 'Add one H1 tag that clearly describes the main topic of your page.',
                'impact_score' => 8,
                'effort_score' => 1,
            ];
        } elseif (count($crawlData['h1_tags']) > 1) {
            $recommendations[] = [
                'category' => 'seo',
                'priority' => 'next',
                'title' => 'Multiple H1 Tags',
                'description' => 'Your page has ' . count($crawlData['h1_tags']) . ' H1 tags. Best practice is to have only one H1 per page.',
                'how_to_fix' => 'Use only one H1 tag for the main heading. Use H2, H3, etc. for subheadings.',
                'impact_score' => 5,
                'effort_score' => 3,
            ];
        }

        if (empty($crawlData['canonical_url'])) {
            $recommendations[] = [
                'category' => 'seo',
                'priority' => 'nice_to_have',
                'title' => 'Missing Canonical URL',
                'description' => 'Your page is missing a canonical URL. This helps prevent duplicate content issues.',
                'how_to_fix' => 'Add a <link rel="canonical" href="..."> tag pointing to the preferred version of this page.',
                'impact_score' => 4,
                'effort_score' => 2,
            ];
        }

        if ($crawlData['images_without_alt'] > 0) {
            $recommendations[] = [
                'category' => 'seo',
                'priority' => 'next',
                'title' => 'Images Missing Alt Text',
                'description' => $crawlData['images_without_alt'] . ' images are missing alt attributes. This hurts SEO and accessibility.',
                'how_to_fix' => 'Add descriptive alt text to all images. Alt text helps search engines understand image content.',
                'impact_score' => 6,
                'effort_score' => 5,
            ];
        }

        foreach ($recommendations as $rec) {
            Recommendation::create([
                'audit_id' => $audit->id,
                'category' => $rec['category'],
                'priority' => $rec['priority'],
                'title' => $rec['title'],
                'description' => $rec['description'],
                'how_to_fix' => $rec['how_to_fix'],
                'impact_score' => $rec['impact_score'],
                'effort_score' => $rec['effort_score'],
            ]);
        }
    }
}
