# Shortcut plugin for Craft CMS
Lets you create short links for elements or arbitrary URLs.

## Installation
You can install Shortcut via the plugin store, or through Composer.

### Craft Plugin Store
To install **Shortcut**, navigate to the _Plugin Store_ section of your Craft control panel, search for `Shortcut`, and click the _Try_ button.

### Composer
You can also add the package to your project using Composer.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:
    
        composer require verbb/shortcut

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Shortcut.

## Usage
To create a short url for a element:

```twig
{% set shortcut = craft.shortcut.get({ element: entry }) %}

{{ shortcut.getUrl() }}
```

To create a short url for a url:

```twig
{% set shortcut = craft.shortcut.get({ url: 'https://cnn.com' }) %}

{{ shortcut.getUrl() }}
```

By default, short urls is in the format `xxxx.tld/s/aBC123`. If you want to change the url segment from `s` to something custom, you can add a config file called shortcut.php in craft/config to override it:

```php
<?php
return [
    // Override Shortcut URL segment
    'urlSegment' => 'x',
];
```

If you want to remove the url segment completely and get urls like `xxxx.tld/aBC123`, you can enable the `hideUrlSegment` option.

```php
<?php
return [
    // Hide url segment
    'hideUrlSegment' => true,
];
```

To use a custom domain, add the domain with the `customDomain` option.

```php
<?php
return [
    // Set custom domain
    'customDomain' => 'https://cool.domain',
];
```

## Credits
Originally created by the team at [Superbig](https://superbig.co/).

## Show your Support
Shortcut is licensed under the MIT license, meaning it will always be free and open source – we love free stuff! If you'd like to show your support to the plugin regardless, [Sponsor](https://github.com/sponsors/verbb) development.

<h2></h2>

<a href="https://verbb.io" target="_blank">
    <img width="100" src="https://verbb.io/assets/img/verbb-pill.svg">
</a>
