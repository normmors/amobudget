<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Services\AmoCrmChangeBudget;

class AmoCrmChangeBudgetJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        AmoCrmChangeBudget::run();
        dump('Интеграция прошла успешно');
    }
    public function failed(\Exception $exception)
    {
        $error = "Ошибка при расчете прибыли \n" . $exception->getMessage();
        dump($error);
    }
}
