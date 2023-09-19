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
    ],
];
```

## Configuration options
- `urlSegment` - Set the URL segment for links. e.g. `my-site.test/s/xxxxx`.
- `hideUrlSegment` - Whether to hide the URL segment for links. e.g. `my-site.test/xxxxx`.
- `customDomain` - Whether to use a custom domain name for links.
- `hashLength` - Control the length of the unique hash.

## Control Panel
You can also manage configuration settings through the Control Panel by visiting Settings → Shortcut.
