<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Services\AmoCrmGetAccessToken;

class AmoCrmGetAccessTokenJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $code;
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        AmoCrmGetAccessToken::run($this->code);
        dump('Access и refresh токены получены успешно');
    }
    public function failed(\Exception $exception)
    {
        $error = "Ошибка при получении access и reresh токена \n" . $exception->getMessage();
        dump($error);
    }
}
