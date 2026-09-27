<?php
namespace verbb\shortcut\controllers;

use verbb\shortcut\Shortcut;
use verbb\shortcut\models\Settings;

use yii\web\Response;

use verbb\base\controllers\SettingsController as BaseSettingsController;

class SettingsController extends BaseSettingsController
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
