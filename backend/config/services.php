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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'notion' => [
        'base_url' => env('NOTION_BASE_URL', 'https://api.notion.com/v1'),
        'api_token' => env('NOTION_API_TOKEN'),
        'database_id' => env('NOTION_DATABASE_ID'),
    ],

    'pinterest' => [
        'base_url' => env('PINTEREST_BASE_URL', 'https://api.pinterest.com/v5'),
        'access_token' => env('PINTEREST_ACCESS_TOKEN'),

    ],

    'spintly' => [
        'base_url' => env('SPINTLY_BASE_URL'),
        'access_token' => env('SPINTLY_ACCESS_TOKEN'),
        'org_id' => env('SPINTLY_ORG_ID'),
        'site_id' => env('SPINTLY_SITE_ID'),
    ],

];
