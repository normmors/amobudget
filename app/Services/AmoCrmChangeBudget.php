<?php

namespace App\Services;

use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Models\CustomFieldsValues\NumericCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\NumericCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\NumericCustomFieldValueModel;

use AmoCRM\Client\AmoCRMApiClient;
use Illuminate\Support\Facades\Storage;
use League\OAuth2\Client\Token\AccessToken;

class AmoCrmChangeBudget
{
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

        $cost_price_id = config('amocrm.cost_price_id');
        $profit_id = config('amocrm.profit_id');

        foreach ($leadsServices as $leadsService) {
            $lead = self::updateLead($leadsService, $cost_price_id, $profit_id);
            $lead = $apiClient->leads()->updateOne($lead);
            /* $price = $leadsService->price;
            
            $customFields = $leadsService->getCustomFieldsValues();
            if (!empty($customFields)) {
                $costPriceField = $customFields->getBy('fieldId', intval($cost_price_id));
            }
            if(!empty($costPriceField) && !empty($price)){
                $costPrice = $costPriceField->getValues()->first()->value;
                $profit = $price - $costPrice;
            } else if (empty($costPriceField) && !empty($price)) {
                $profit = $price;
            } else if (!empty($costPriceField) && empty($price)){
                $profit = 0 - $costPrice;
            };
            $lead = $leadsService->setCustomFieldsValues(
                (new CustomFieldsValuesCollection)
                    ->add(
                        (new NumericCustomFieldValuesModel)
                        ->setFieldId($profit_id)
                        ->setValues((new NumericCustomFieldValueCollection)
                        ->add((new NumericCustomFieldValueModel)->setValue($profit)))
                )
            );
            $fieldsCollection = new CustomFieldsValuesCollection;
            $numCustomFieldValuesModel = new NumericCustomFieldValuesModel;
            $numCustomFieldCollection = new NumericCustomFieldValueCollection;
            $numCustomFieldValueModel = new NumericCustomFieldValueModel;
            $leadSetFields = $leadsService->setCustomFieldsValues(
                $fieldsCollection->add(
                        $numCustomFieldValuesModel->setFieldId($profit_id)
                        ->setValues($numCustomFieldCollection
                            ->add($numCustomFieldValueModel
                                ->setValue($profit)
                            )
                        )
                    )
            );
          

            $lead = $apiClient->leads()->updateOne($lead); */
        }
    }
    public static function updateLead($leadsService, $cost_price_id, $profit_id) {
            $price = $leadsService->price;
            
            $customFields = $leadsService->getCustomFieldsValues();
            if (!empty($customFields)) {
                $costPriceField = $customFields->getBy('fieldId', intval($cost_price_id));
            }
            if (!empty($costPriceField) && !empty($price)){
                $costPrice = $costPriceField->getValues()->first()->value;
                $profit = $price - $costPrice;
            } else if (!empty($costPriceField) && empty($price)){
                $costPrice = $costPriceField->getValues()->first()->value;
                $profit = 0 - $costPrice;
            } else if (empty($costPriceField) && !empty($price)) {
                $profit = $price;
            };
            /* $lead = $leadsService->setCustomFieldsValues((new CustomFieldsValuesCollection)
                    ->add((new NumericCustomFieldValuesModel)
                        ->setFieldId($profit_id)
                        ->setValues((new NumericCustomFieldValueCollection)
                        ->add((new NumericCustomFieldValueModel)
                        ->setValue($profit)))
                )
            ); */
            $fieldsCollection = new CustomFieldsValuesCollection;
            $numCustomFieldValuesModel = new NumericCustomFieldValuesModel;
            $numCustomFieldCollection = new NumericCustomFieldValueCollection;
            $numCustomFieldValueModel = new NumericCustomFieldValueModel;
            $lead = $leadsService->setCustomFieldsValues($fieldsCollection
                ->add($numCustomFieldValuesModel
                    ->setFieldId($profit_id)     
                    ->setValues($numCustomFieldCollection
                    ->add($numCustomFieldValueModel
                    ->setValue($profit)))
                )
            );
            return $lead;
    }
}