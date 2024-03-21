<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class AmoCrmGetAccessToken
{
    public static function run($code) {
        $apiClient = new AmoCrmClient;
        $apiClient = $apiClient->getApiClient();
        
        $token = $apiClient->getOAuthClient()->getAccessTokenByCode($code);
        
        Storage::put('amocrm/token.json', json_encode($token->jsonSerialize()));
    }
}