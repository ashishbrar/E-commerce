<?php

namespace App\Providers;

use App\Models\PaypalCredential;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class PayPalConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        try {
            if (!Schema::hasTable('paypal_credentials')) {
                return;
            }

            $settings = PaypalCredential::first();
        } catch (QueryException $exception) {
            return;
        }

        if ($settings) {
            $mode = $settings->paypal_method === 'live' ? 'live' : 'sandbox';

            config([
                'paypal' => [
                    'mode' => $mode,

                    'sandbox' => [
                        'client_id'     => $settings->client_id,
                        'client_secret' => $settings->client_secret,
                        'app_id'        => 'APP-80W284485P519543T', 
                    ],

                    'live' => [
                        'client_id'     => $settings->client_id,
                        'client_secret' => $settings->client_secret,
                        'app_id'        => '', 
                    ],

                    'payment_action' => 'Sale',
                    'currency'       => 'USD',
                    'notify_url'     => '',  
                    'locale'         => 'en_US',
                    'validate_ssl'   => true,
                ],
            ]);
        }
    }
}
