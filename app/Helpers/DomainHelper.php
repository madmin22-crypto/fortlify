<?php

namespace App\Helpers;

class DomainHelper
{
    public static function isAppContext(): bool
    {
        $host = request()->getHost();
        
        return str_starts_with($host, 'app.') || $host === 'app.fortlify.com';
    }

    public static function isMarketingContext(): bool
    {
        return !self::isAppContext();
    }
}
