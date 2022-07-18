<?php
namespace verbb\shortcut\variables;

use verbb\shortcut\Shortcut;
use verbb\shortcut\models\Shortcut;

class ShortcutVariable
{
    // Public Methods
    // =========================================================================

    public function get(array $options = []): ?Shortcut
    {
        return Shortcut::$plugin->getService()->get($options);
    }
}
