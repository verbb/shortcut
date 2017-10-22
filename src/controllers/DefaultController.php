ﬁ<?php
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
     * @return mixed
     */
    public function actionIndex ()
    {
        if ( isset($variables['code']) ) {
            $shortcut = Shortcut::$plugin->shortcutService->getByCode($variables['code']);

            if ( $shortcut ) {
                craft()->shortcut->increaseHits($shortcut);
                $this->redirect($shortcut->getRealUrl());
            }
        }

        $this->redirect('/');
    }

    /**
     * @return mixed
     */
    public function actionDoSomething ()
    {
        $result = 'Welcome to the DefaultController actionDoSomething() method';

        return $result;
    }
}
