<?php

namespace App\Console\Commands;

use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Models\CustomFieldsValues\NumericCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\NumericCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\NumericCustomFieldValueModel;
use Illuminate\Console\Command;

use AmoCRM\Client\AmoCRMApiClient;
use Illuminate\Support\Facades\Storage;
use League\OAuth2\Client\Token\AccessToken;

use App\Jobs\AmoCrmGetAccessTokenJob;
use App\Jobs\AmoCrmChangeBudgetJob;
class AmoCrmCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:amo-crm {code?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Интеграция AmoCrm';

    /**
     * Execute the console command.
     */
    public function handle()
    {   
        $code = $this->argument('code');
        if ($code) {
            AmoCrmGetAccessTokenJob::dispatch($code);
            sleep(1);
            AmoCrmChangeBudgetJob::dispatch();
        } else {
            AmoCrmChangeBudgetJob::dispatch();
        }
    }
}
