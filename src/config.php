<?php

return [
    // Override Shortcut URL segment
    'urlSegment' => 'x',

    // Hide url segment
    'hideUrlSegment' => true,

    // Set custom domain
    'customDomain' => 'https://cool.domain',

    // Set the hash length
    'hashLength' => 12,

    // Set the provider used to create shortcuts (`local`, `bitly`, `tinyurl`, `isgd`, `rebrandly`, `shortio`)
    'provider' => 'local',

    // Set a Bitly access token, required when using the `bitly` provider
    'bitlyAccessToken' => '$BITLY_ACCESS_TOKEN',

    // Set Rebrandly credentials, required when using the `rebrandly` provider
    'rebrandlyApiKey' => '$REBRANDLY_API_KEY',
    'rebrandlyDomain' => '$REBRANDLY_DOMAIN',
    'rebrandlyWorkspaceId' => '$REBRANDLY_WORKSPACE_ID',

    // Set Short.io credentials, required when using the `shortio` provider
    'shortioApiKey' => '$SHORTIO_API_KEY',
    'shortioDomain' => '$SHORTIO_DOMAIN',
];