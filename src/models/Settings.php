<?php
namespace verbb\shortcut\models;

use craft\base\Model;

class Settings extends Model
{
    // Properties
    // =========================================================================

    /**
     * @var string
     */
    public $urlSegment = '';

    /**
     * @var string
     */
    public $hideUrlSegment = false;

    /**
     * @var string
     */
    public $customDomain = '';

    /**
     * @var int
     */
    public $hashLength = 12;


    // Public Methods
    // =========================================================================

    public function defineRules(): array
    {
        $rules = parent::defineRules();

        $rules[] = [['urlSegment', 'customDomain'], 'string'];
        $rules[] = [['hideUrlSegment'], 'bool'];

        return $rules;
    }
}
