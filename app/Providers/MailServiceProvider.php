<?php

namespace App\Providers;

use App\Mail\Transport\MicrosoftGraphTransport;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class MailServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Mail::extend('graph', function (array $config) {
            return new MicrosoftGraphTransport(
                config('services.azure.tenant_id'),
                config('services.azure.client_id'),
                config('services.azure.client_secret'),
                config('services.azure.email_user')
            );
        });
    }
}
