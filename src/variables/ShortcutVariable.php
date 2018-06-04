<?php
/**
 * Shortcut plugin for Craft CMS 3.x
 *
 * Simple URL shortening
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2017 Superbig
 */

namespace superbig\shortcut\variables;

use superbig\shortcut\models\ShortcutModel;
use superbig\shortcut\Shortcut;

use Craft;

/**
 * @author    Superbig
 * @package   Shortcut
 * @since     1.0.0
 */
class ShortcutVariable
{
    // Public Methods
    // =========================================================================

    /**
     * @param array $options
     *
     * @return ShortcutModel|null
     */
    public function get ($options = [])
    {
        return Shortcut::$plugin->shortcutService->get($options);
    }
}
