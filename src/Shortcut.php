<?php
namespace verbb\shortcut;

use verbb\shortcut\base\PluginTrait;
use verbb\shortcut\models\Settings;
use verbb\shortcut\variables\ShortcutVariable;

use Craft;
use craft\base\Plugin;
use craft\events\ElementEvent;
use craft\events\ExceptionEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\services\Elements;
use craft\web\ErrorHandler;
use craft\web\UrlManager;
use craft\web\twig\variables\CraftVariable;

use yii\base\Event;
use yii\web\HttpException;

use Twig\Error\RuntimeError;

class Shortcut extends Plugin
{
    // Properties
    // =========================================================================

    public string $schemaVersion = '2.1.1';


    // Traits
    // =========================================================================

    use PluginTrait;


    // Public Methods
    // =========================================================================

    public function init(): void
    {
        parent::init();

        self::$plugin = $this;

        $this->_setPluginComponents();
        $this->_setLogging();
        $this->_registerSiteRoutes();
        $this->_registerVariables();
        $this->_registerCraftEventListeners();

        $request = Craft::$app->getRequest();

        if ($request->getIsSiteRequest() && !$request->getIsConsoleRequest()) {
            $this->_handleSiteRequest();
        }
    }


    // Protected Methods
    // =========================================================================

    protected function createSettingsModel(): Settings
    {
        return new Settings();
    }


    // Private Methods
    // =========================================================================

    private function _registerVariables(): void
    {
        Event::on(CraftVariable::class, CraftVariable::EVENT_INIT, function(Event $event) {
            $event->sender->set('shortcut', ShortcutVariable::class);
        });
    }

    private function _registerSiteRoutes(): void
    {
        $urlSegment = $this->getSettings()->urlSegment . '/<code:\w+>';

        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_SITE_URL_RULES, function(RegisterUrlRulesEvent $event) use ($urlSegment) {
            $event->rules[$urlSegment] = 'shortcut/base/get';
        });
    }

    private function _registerCraftEventListeners(): void
    {
        Event::on(Elements::class, Elements::EVENT_AFTER_SAVE_ELEMENT, function(ElementEvent $event) {
            if (!$event->isNew) {
                $this->getService()->onSaveElement($event->element);
            }
        });

        Event::on(Elements::class, Elements::EVENT_AFTER_DELETE_ELEMENT, function(ElementEvent $event) {
            $this->getService()->onDeleteElement($event->element);
        });
    }

    private function _handleSiteRequest(): void
    {
        Event::on(ErrorHandler::class, ErrorHandler::EVENT_BEFORE_HANDLE_EXCEPTION, function(ExceptionEvent $event) {
            $exception = $event->exception;

            // If this is a Twig Runtime exception, use the previous one instead
            if ($exception instanceof RuntimeError && ($previousException = $exception->getPrevious()) !== null) {
                $exception = $previousException;
            }

            // If this is a 404 error, see if we can handle it
            if ($exception instanceof HttpException && $exception->statusCode === 404) {
                Shortcut::$plugin->getService()->on404();
            }
        });
    }

}
