# Configuration
Create a `shortcut.php` file under your `/config` directory with the following options available to you. You can also use multi-environment options to change these per environment.

The below shows the defaults already used by Shortcut, so you don't need to add these options unless you want to modify the values.

```php
<?php

return [
    '*' => [
        'urlSegment' => 's',
        'hideUrlSegment' => false,
        'customDomain' => '',
        'hashLength' => 12,
        'provider' => 'local',
        'bitlyAccessToken' => '',
        'rebrandlyApiKey' => '',
        'rebrandlyDomain' => '',
        'rebrandlyWorkspaceId' => '',
        'shortioApiKey' => '',
        'shortioDomain' => '',
    ],
];
```

## Configuration options
- `urlSegment` - Set the URL segment for links. e.g. `my-site.test/s/xxxxx`.
- `hideUrlSegment` - Whether to hide the URL segment for links. e.g. `my-site.test/xxxxx`.
- `customDomain` - Whether to use a custom domain name for links.
- `hashLength` - Control the length of the unique hash.
- `provider` - The provider used to create shortened URLs. Supported values are `local`, `bitly`, `tinyurl`, `isgd`, `rebrandly`, and `shortio`.
- `bitlyAccessToken` - A Bitly access token, required when `provider` is set to `bitly`. Environment variables are supported.
- `rebrandlyApiKey` - A Rebrandly API key, required when `provider` is set to `rebrandly`. Environment variables are supported.
- `rebrandlyDomain` - An optional Rebrandly branded domain, such as `rebrand.ly`. Environment variables are supported.
- `rebrandlyWorkspaceId` - An optional Rebrandly workspace ID for accounts with multiple workspaces. Environment variables are supported.
- `shortioApiKey` - A Short.io API key, required when `provider` is set to `shortio`. Environment variables are supported.
- `shortioDomain` - A Short.io domain, required when `provider` is set to `shortio`. Environment variables are supported.

## Control Panel
You can also manage configuration settings through the Control Panel by visiting Settings → Shortcut.
