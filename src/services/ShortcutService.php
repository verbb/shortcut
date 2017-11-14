<?php
/**
 * Shortcut plugin for Craft CMS 3.x
 *
 * Simple URL shortening
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2017 Superbig
 */

namespace superbig\shortcut\services;

use craft\base\Element;
use Hashids\Hashids;
use superbig\shortcut\models\ShortcutModel;
use superbig\shortcut\records\ShortcutRecord;
use superbig\shortcut\Shortcut;

use Craft;
use craft\base\Component;

/**
 * @author    Superbig
 * @package   Shortcut
 * @since     1.0.0
 */
class ShortcutService extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * @param array $options
     *
     * @return null|ShortcutModel|Shortcut
     */
    public function get ($options = [])
    {
        if ( isset($options['element']) ) {
            $element = $options['element'];

            // Check if we have one
            $shortcut = $this->getByElementId($element->id, $element->siteId);

            // If not, create one
            if ( !$shortcut ) {
                $shortcut = $this->create($options);
            }

            return $shortcut;
        }

        if ( isset($options['url']) ) {
            $url = $options['url'];

            // Check if we have one
            $shortcut = $this->getByUrl($url);

            // If not, create one
            if ( !$shortcut ) {
                $shortcut = $this->create($options);
            }

            return $shortcut;
        }

        return null;
    }

    /**
     * @param array $options
     *
     * @return ShortcutModel
     */
    public function create ($options = [])
    {
        $model = new ShortcutModel();

        if ( isset($options['element']) ) {
            $element = $options['element'];
            $url     = $element->getUrl();

            $model->elementId   = $element->id;
            $model->elementType = get_class($element);
            $model->siteId      = $element->siteId;
            $model->url         = $url;
            $model->urlHash     = $this->_hashForUrl($url);
        }

        if ( isset($options['url']) ) {
            $url            = $options['url'];
            $model->url     = $url;
            $model->urlHash = $this->_hashForUrl($url);
        }

        $this->saveShortcut($model);

        return $model;
    }

    /**
     * @param null $id
     *
     * @return null|ShortcutModel
     */
    public function getById ($id = null)
    {
        $record = ShortcutRecord::findById($id);

        if ( $record ) {
            return $this->_populateShortcut($record);
        }

        return null;
    }

    /**
     * @param null $code
     *
     * @return null|ShortcutModel
     */
    public function getByCode ($code = null)
    {
        $record = ShortcutRecord::findOne([ 'code' => $code ]);

        if ( $record ) {
            return $this->_populateShortcut($record);
        }

        return null;
    }

    /**
     * @param null $url
     *
     * @return null|ShortcutModel
     */
    public function getByUrl ($url = null): ShortcutModel
    {
        $hash = $this->_hashForUrl($url);

        $record = ShortcutRecord::findOne([ 'urlHash' => $hash ]);

        if ( $record ) {
            return $this->_populateShortcut($record);
        }

        return null;
    }

    /**
     * @param null $id
     * @param null $siteId
     *
     * @return ShortcutModel|null
     */
    public function getByElementId ($id = null, $siteId = null)
    {
        $record = ShortcutRecord::findOne([ 'elementId' => $id, 'siteId' => $siteId ]);

        if ( $record ) {
            return $this->_populateShortcut($record);
        }

        return null;
    }

    /**
     * @param ShortcutModel $shortcut
     */
    public function increaseHits (ShortcutModel $shortcut)
    {
        $shortcut->hits = $shortcut->hits + 1;

        $this->saveShortcut($shortcut);
    }

    /**
     * @param ShortcutModel $shortcut
     */
    public function saveShortcut (ShortcutModel &$shortcut)
    {

        $isNew = !$shortcut->id;

        if ( $shortcut->validate() ) {
            if ( !$isNew ) {
                $record = ShortcutRecord::findOne($shortcut->id);

                if ( !$record ) {
                    throw new Exception('No shortcut record with ID ' . $shortcut->id . ' was found.');
                }
            }
            else {
                $record = new ShortcutRecord();
            }

            $record->url         = $shortcut->url;
            $record->urlHash     = $shortcut->urlHash;
            $record->code        = $shortcut->code;
            $record->siteId      = $shortcut->siteId;
            $record->hits        = $shortcut->hits;
            $record->elementId   = $shortcut->elementId;
            $record->elementType = $shortcut->elementType;

            if ( $record->save() && empty($record->code) ) {
                $hashids = new Hashids(Craft::$app->security->generateRandomKey(), 5);

                $code         = $hashids->encode($record->id);
                $record->code = $code;

                if ( $record->save() ) {
                    $shortcut->code = $code;
                }

            }

        }

    }

    /**
     * @param Element $element
     */
    public function onSaveElement (Element $element)
    {
        $shortcut = $this->getByElementId($element->id, $element->siteId);

        if ( $shortcut ) {
            // Check if we should update the url

            if ( $element->getUrl() !== $shortcut->url ) {
                $shortcut->url = $element->getUrl();

                $this->saveShortcut($shortcut);
            }
        }
    }

    /**
     * @return null|void
     */
    public function on404 ()
    {
        $code     = Craft::$app->request->getSegment(1);
        $shortcut = $this->getByCode($code);

        if ( $shortcut ) {
            //ShortcutPlugin::log(Craft::t('Found matching shortcut {code}, redirecting to {url}', [ 'code' => $code, 'url' => $shortcut->getRealUrl() ]), LogLevel::Info, false);

            $this->increaseHits($shortcut);

            Craft::$app->response->redirect($shortcut->getRealUrl());

            return Craft::$app->end();
        }

        return null;
    }

    /**
     * @param null $url
     *
     * @return string
     */
    private function _hashForUrl ($url = null)
    {
        return md5($url);
    }

    /**
     * @param ShortcutRecord $record
     *
     * @return ShortcutModel
     */
    private function _populateShortcut (ShortcutRecord $record)
    {
        $model              = new ShortcutModel();
        $model->id          = $record->id;
        $model->siteId      = $record->siteId;
        $model->elementId   = $record->elementId;
        $model->elementType = $record->elementType;
        $model->hits        = $record->hits;
        $model->url         = $record->url;
        $model->urlHash     = $record->urlHash;
        $model->code        = $record->code;

        return $model;
    }
}
