<?php

namespace App\Services;

use AmoCRM\Client\AmoCRMApiClient;

class AmoCrmClient
{
    public function getApiClient()
    {
        $client_id = config('amocrm.client_id');
        $client_secret = config('amocrm.client_secret');
        $redirect_uri = config('amocrm.redirect_uri');
        $account_domain = config('amocrm.account_domain');
        
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