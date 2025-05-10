<?php

namespace App\Helpers;

class MidtransHelper
{
    public static function getEnv()
    {
        $env = env('MIDTRANS_ENV', 'sandbox'); // default: sandbox

        $config = [
            'sandbox' => [
                'client_key' => env('MIDTRANS_SANDBOX_CLIENT_KEY'),
                'server_key' => env('MIDTRANS_SANDBOX_SERVER_KEY'),
                'url' => env('MIDTRANS_SANDBOX_TRANSACTION_API_URL'), // untuk payment link
                'status_url_base' => 'https://api.sandbox.midtrans.com/v2/',
            ],
            'production' => [
                'client_key' => env('MIDTRANS_PRODUCTION_CLIENT_KEY'),
                'server_key' => env('MIDTRANS_PRODUCTION_SERVER_KEY'),
                'url' => env('MIDTRANS_PRODUCTION_TRANSACTION_API_URL'), // untuk payment link
                'status_url_base' => 'https://api.midtrans.com/v2/',
            ],
        ];

        return $config[$env];
    }

    public static function getStatusUrl($orderId)
    {
        $envConfig = self::getEnv();
        return $envConfig['status_url_base'] . $orderId . '/status';
    }
}
