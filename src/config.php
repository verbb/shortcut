<?php
/**
 * Shortcut plugin for Craft CMS 3.x
 *
 * Simple URL shortening
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2017 Superbig
 */

/**
 * @author    Superbig
 * @package   Shortcut
 * @since     1.0.0
 */

/**
 * Shortcut config.php
 *
 * This file exists only as a template for the Shortcut settings.
 * It does nothing on its own.
 *
 * Don't edit this file, instead copy it to 'craft/config' as 'shortcut.php'
 * and make your changes there to override default settings.
 *
 * Once copied to 'craft/config', this file will be multi-environment aware as
 * well, so you can have different settings groups for each environment, just as
 * you do for 'general.php'
 */

return [
    // Override Shortcut URL segment
    'urlSegment'     => 'x',

    // Hide url segment
    'hideUrlSegment' => true,

    // Set custom domain
    'customDomain'   => 'https://cool.domain',
];