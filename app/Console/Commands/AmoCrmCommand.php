<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\AmoCrmGetAccessTokenJob;
use App\Jobs\AmoCrmChangeBudgetJob;

class AmoCrmCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:amo-crm {code?}'; //code = Код авторизации

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
