<?php
namespace verbb\shortcut\controllers;

use verbb\shortcut\Shortcut;
use verbb\shortcut\models\Settings;

use craft\web\Controller;

use yii\web\Response;

class SettingsController extends Controller
{
    // Public Methods
    // =========================================================================

    public function actionIndex(): Response
    {
        /* @var Settings $settings */
        $settings = Shortcut::$plugin->getSettings();

        return $this->renderTemplate('shortcut/settings', [
            'settings' => $settings,
            'providerOptions' => Settings::getProviderOptions(),
        ]);
    }
}
