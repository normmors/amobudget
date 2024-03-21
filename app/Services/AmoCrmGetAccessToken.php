<?php

namespace App\Services;

use AmoCRM\Client\AmoCRMApiClient;
use Illuminate\Support\Facades\Storage;

class AmoCrmGetAccessToken
{
    public static function run($code) {
        $apiClient = new AmoCrmClient;
        $apiClient = $apiClient->getApiClient();
        /* $client_id = config('amocrm.client_id');
        $client_secret = config('amocrm.client_secret');
        $redirect_uri = config('amocrm.redirect_uri');
        $account_domain = config('amocrm.account_domain');
        // $code = config('amocrm.code');
        
        $apiClient = new AmoCRMApiClient($client_id, $client_secret, $redirect_uri);
        $apiClient->setAccountBaseDomain($account_domain); */
        
        $token = $apiClient->getOAuthClient()->getAccessTokenByCode($code);
        
        Storage::put('amocrm/token.json', json_encode($token->jsonSerialize()));
    }
}