<?php

namespace App\Services;

use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Models\CustomFieldsValues\NumericCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\NumericCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\NumericCustomFieldValueModel;

use Illuminate\Support\Facades\Storage;
use League\OAuth2\Client\Token\AccessToken;

class AmoCrmChangeBudget
{
    private static $cost_price_id; //id поля 'Себестоимость'
    private static $profit_id; //id поля 'Прибыль'
    private static function initializeCustomsFieldsIds() {
        self::$cost_price_id = env('COST_PRICE_ID');
        self::$profit_id = env('PROFIT_ID');
    }
    public static function run() {
        $apiClient = new AmoCrmClient;
        $apiClient = $apiClient->getApiClient();
        
        $token = json_decode(Storage::get('amocrm/token.json'), 1);
        $token = new AccessToken($token);

        $apiClient->setAccessToken($token);
        $apiClient->onAccessTokenRefresh(function ($token){
            Storage::put('amocrm/token.json', json_encode($token->jsonSerialize()));
        });

        $leadsServices = $apiClient->leads()->get();
        if (empty($leadsServices)) {
            throw new \Exception('0 сделок');
        } else 

        self::initializeCustomsFieldsIds();
        foreach ($leadsServices as $leadsService) {
            $lead = self::updateLead($leadsService);
            $lead = $apiClient->leads()->updateOne($lead);
        }
    }
    private static function updateLead($leadsService) {
        $price = $leadsService->price;
        $customFields = $leadsService->getCustomFieldsValues();
        if (!empty($customFields)) {
            $costPriceField = $customFields->getBy('fieldId', intval(self::$cost_price_id));
        }

        if (!empty($costPriceField)) {
            $costPrice = $costPriceField->getValues()->first()->value;
            if (!empty($price)) {
                $profit = $price - $costPrice;
            } else $profit = 0 - $costPrice;
        } else {
            if (!empty($price)) {
                $profit = $price;
            } else $profit = 0;
        }

        $lead = self::collectLead($leadsService, $profit);
        return $lead;
    }
    private static function collectLead($leadsService, $profit) {
        $fieldsCollection = new CustomFieldsValuesCollection;
        $numCustomFieldValuesModel = new NumericCustomFieldValuesModel;
        $numCustomFieldCollection = new NumericCustomFieldValueCollection;
        $numCustomFieldValueModel = new NumericCustomFieldValueModel;
        $lead = $leadsService->setCustomFieldsValues($fieldsCollection
            ->add($numCustomFieldValuesModel
                ->setFieldId(self::$profit_id)     
                ->setValues($numCustomFieldCollection
                ->add($numCustomFieldValueModel
                ->setValue($profit)))
            )
        );
        return $lead;
    }
}