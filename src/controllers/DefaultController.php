<?php
/**
 * Shortcut plugin for Craft CMS 3.x
 *
 * Simple URL shortening
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2017 Superbig
 */

namespace superbig\shortcut\controllers;

use superbig\shortcut\Shortcut;

use Craft;
use craft\web\Controller;

/**
 * @author    Superbig
 * @package   Shortcut
 * @since     1.0.0
 */
class DefaultController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = [ 'get' ];

    // Public Methods
    // =========================================================================

    /**
     * @param null $code
     *
     * @return mixed
     */
    public function actionGet ($code = null)
    {
        if ( isset($code) ) {
            $shortcut = Shortcut::$plugin->shortcutService->getByCode($code);

            if ( $shortcut ) {
                Shortcut::$plugin->shortcutService->increaseHits($shortcut);

                return $this->redirect($shortcut->getRealUrl());
            }
        }

        return $this->redirect('/');
    }
}
