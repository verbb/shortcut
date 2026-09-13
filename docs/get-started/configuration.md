# Configuration

You can customise Shortcut’s settings using a PHP configuration file. This is optional: each setting has a default, so you only need to include the values you want to change.

To override a setting, create `shortcut.php` in your Craft project’s `/config` directory and return an array of setting names and values. For example, the following will use `go` as the short-link URL segment:

```php
<?php

return [
    'urlSegment' => 'go',
];
```

All other settings keep their defaults. Add any further settings you want to change to the same array. The options below explain the available settings and their defaults.

## Configuration Options

::: reference
### `urlSegment`

**Type:** `string` · **Default:** `'s'`

Set the URL segment for links. e.g. `my-site.test/s/xxxxx`.
:::

::: reference
### `hideUrlSegment`

**Type:** `bool` · **Default:** `false`

Whether to hide the URL segment for links. e.g. `my-site.test/xxxxx`.
:::

::: reference
### `customDomain`

**Type:** `string` · **Default:** `''`

Whether to use a custom domain name for links.
:::

::: reference
### `hashLength`

**Type:** `int` · **Default:** `12`

Control the length of the unique hash.
:::

::: reference
### `provider`

**Type:** `string` · **Default:** `'local'`

The provider used to create shortened URLs. Supported values are `local`, `bitly`, `tinyurl`, `isgd`, `rebrandly`, and `shortio`.
:::

::: reference
### `bitlyAccessToken`

**Type:** `string` · **Default:** `''`

A Bitly access token, required when `provider` is set to `bitly`. Environment variables are supported.
:::

::: reference
### `rebrandlyApiKey`

**Type:** `string` · **Default:** `''`

A Rebrandly API key, required when `provider` is set to `rebrandly`. Environment variables are supported.
:::

::: reference
### `rebrandlyDomain`

**Type:** `string` · **Default:** `''`

An optional Rebrandly branded domain, such as `rebrand.ly`. Environment variables are supported.
:::

::: reference
### `rebrandlyWorkspaceId`

**Type:** `string` · **Default:** `''`

An optional Rebrandly workspace ID for accounts with multiple workspaces. Environment variables are supported.
:::

::: reference
### `shortioApiKey`

**Type:** `string` · **Default:** `''`

A Short.io API key, required when `provider` is set to `shortio`. Environment variables are supported.
:::

::: reference
### `shortioDomain`

**Type:** `string` · **Default:** `''`

A Short.io domain, required when `provider` is set to `shortio`. Environment variables are supported.
:::


## Control Panel
You can also manage configuration settings through the Control Panel by visiting Settings → Shortcut.
