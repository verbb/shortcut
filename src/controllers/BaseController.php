<?php
namespace verbb\shortcut\controllers;

use verbb\shortcut\Shortcut;

use craft\web\Controller;

use yii\base\Exception;
use yii\web\Response;

class BaseController extends Controller
{
    // Properties
    // =========================================================================

    protected $allowAnonymous = ['get'];


    // Public Methods
    // =========================================================================

    public function actionGet($code = null): Response
    {
        if (isset($code)) {
            $shortcut = Shortcut::$plugin->getService()->getByCode($code);

            if ($shortcut) {
                Shortcut::$plugin->getService()->increaseHits($shortcut);

                return $this->redirect($shortcut->getRealUrl());
            }
        }

        return $this->redirect('/');
    }
}
