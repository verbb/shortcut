# Usage
To create a short url for an element:

```twig
{% set shortcut = craft.shortcut.get({ element: entry }) %}

{{ shortcut.getUrl() }}
```

To create a short url for a url:

```twig
{% set shortcut = craft.shortcut.get({ url: 'https://my-site.test' }) %}

{{ shortcut.getUrl() }}
```
