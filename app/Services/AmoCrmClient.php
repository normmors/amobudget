<?php

namespace App\Services;

use AmoCRM\Client\AmoCRMApiClient;

class AmoCrmClient
{
    public static function getApiClient()
    {
        $client_id = env('CLIENT_ID');
        $client_secret = env('CLIENT_SECRET');
        $redirect_uri = env('REDIRECT_URI');
        $account_domain = env('ACCOUNT_DOMAIN');
        
        $apiClient = new AmoCRMApiClient
        (
            $client_id, 
            $client_secret, 
            $redirect_uri
        );
        $apiClient->setAccountBaseDomain($account_domain);
        return $apiClient;
    }
}