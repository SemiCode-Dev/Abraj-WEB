<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'tbo' => [
        'base_url' => env('TBO_BASE_URL'),
        'username' => env('TBO_USERNAME'),
        'password' => env('TBO_PASSWORD'),
    ],

    'aps' => [
        'merchant_id' => env('APS_MERCHANT_ID'),
        'access_code' => env('APS_ACCESS_CODE'),
        'sha_request' => env('APS_SHA_REQUEST'),
        'sha_response' => env('APS_SHA_RESPONSE'),
        'payment_url' => env('APS_PAYMENT_URL'),
        'callback_url' => env('APS_CALLBACK_URL'),
    ],

    'sms' => [
        'enabled' => env('SMS_ENABLED', false),
        'provider' => env('SMS_PROVIDER', 'log'), // Options: twilio, vonage, unifonic, log
        'from' => env('SMS_FROM'),
        
        // Twilio configuration
        'twilio' => [
            'sid' => env('TWILIO_SID'),
            'token' => env('TWILIO_AUTH_TOKEN'),
        ],
        
        // Vonage configuration
        'vonage' => [
            'api_key' => env('VONAGE_API_KEY'),
            'api_secret' => env('VONAGE_API_SECRET'),
        ],
        
        // Unifonic configuration
        'unifonic' => [
            'app_sid' => env('UNIFONIC_APP_SID'),
        ],
    ],

    'azure' => [
        'tenant_id' => env('AZURE_TENANT_ID'),
        'client_id' => env('AZURE_CLIENT_ID'),
        'client_secret' => env('AZURE_CLIENT_SECRET'),
        'email_user' => env('AZURE_EMAIL_USER'),
    ],

];
