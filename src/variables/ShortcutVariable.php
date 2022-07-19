<?php
namespace verbb\shortcut\variables;

use verbb\shortcut\Shortcut as ShortcutPlugin;
use verbb\shortcut\models\Shortcut;

class ShortcutVariable
{
    // Public Methods
    // =========================================================================

    public function get(array $options = []): ?Shortcut
    {
        return ShortcutPlugin::$plugin->getService()->get($options);
    }
}
