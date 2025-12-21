<?php

use App\Helpers\DomainHelper;

if (!function_exists('isAppContext')) {
    function isAppContext(): bool
    {
        return DomainHelper::isAppContext();
    }
}

if (!function_exists('isMarketingContext')) {
    function isMarketingContext(): bool
    {
        return DomainHelper::isMarketingContext();
    }
}
