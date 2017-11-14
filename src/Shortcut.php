<?php
/**
 * Shortcut plugin for Craft CMS 3.x
 *
 * Simple URL shortening
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2017 Superbig
 */

namespace superbig\shortcut;

use craft\base\Element;
use craft\events\ElementEvent;
use craft\services\Elements;
use superbig\shortcut\models\Settings;
use superbig\shortcut\services\ShortcutService as ShortcutServiceService;
use superbig\shortcut\variables\ShortcutVariable;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\UrlManager;
use craft\web\twig\variables\CraftVariable;
use craft\events\RegisterUrlRulesEvent;

use yii\base\Event;

/**
 * Class Shortcut
 *
 * @author    Superbig
 * @package   Shortcut
 * @since     1.0.0
 *
 * @property  ShortcutServiceService $shortcutService
 */
class Shortcut extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var Shortcut
     */
    public static $plugin;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init ()
    {
        parent::init();
        self::$plugin = $this;

        $urlSegment = $this->getSettings()->urlSegment ?: 's';
        $urlSegment = $urlSegment . '/<code:\w+>';

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) use ($urlSegment) {
                $event->rules[ $urlSegment ] = 'shortcut/default/get';
            }
        );

        /*Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['cpActionTrigger1'] = 'shortcut/default/do-something';
            }
        );*/

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('shortcut', ShortcutVariable::class);
            }
        );

        Event::on(
            Elements::class,
            Elements::EVENT_AFTER_SAVE_ELEMENT,
            function (ElementEvent $event) {
                if ( !$event->isNew ) {
                    $this->shortcutService->onSaveElement($event->element);
                }
            }
        );

        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ( $event->plugin === $this ) {
                }
            }
        );

        Craft::info(
            Craft::t(
                'shortcut',
                '{name} plugin loaded',
                [ 'name' => $this->name ]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

    protected function createSettingsModel ()
    {
        return new Settings();
    }

}
