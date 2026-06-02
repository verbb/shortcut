# Usage
To create a short url for an element:

```twig
{% set shortcut = craft.shortcut.get({ element: entry }) %}

{{ shortcut.getUrl() }}
```

If an external provider is configured, `getUrl()` will return the shortened URL from that provider. Otherwise, it returns Shortcut’s local URL.

To create a short url for a url:

```twig
{% set shortcut = craft.shortcut.get({ url: 'https://my-site.test' }) %}

{{ shortcut.getUrl() }}
```
