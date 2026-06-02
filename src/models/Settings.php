<?php
namespace verbb\shortcut\models;

use craft\base\Model;

class Settings extends Model
{
    // Constants
    // =========================================================================

    public const PROVIDER_LOCAL = 'local';
    public const PROVIDER_BITLY = 'bitly';
    public const PROVIDER_TINYURL = 'tinyurl';
    public const PROVIDER_ISGD = 'isgd';
    public const PROVIDER_REBRANDLY = 'rebrandly';
    public const PROVIDER_SHORTIO = 'shortio';


    // Properties
    // =========================================================================

    public string $urlSegment = 's';
    public bool $hideUrlSegment = false;
    public string $customDomain = '';
    public int $hashLength = 12;
    public string $provider = self::PROVIDER_LOCAL;
    public string $bitlyAccessToken = '';
    public string $rebrandlyApiKey = '';
    public string $rebrandlyDomain = '';
    public string $rebrandlyWorkspaceId = '';
    public string $shortioApiKey = '';
    public string $shortioDomain = '';


    // Static Methods
    // =========================================================================

    public static function getProviderOptions(): array
    {
        return [
            ['label' => 'Shortcut', 'value' => self::PROVIDER_LOCAL],
            ['label' => 'Bitly', 'value' => self::PROVIDER_BITLY],
            ['label' => 'TinyURL', 'value' => self::PROVIDER_TINYURL],
            ['label' => 'is.gd', 'value' => self::PROVIDER_ISGD],
            ['label' => 'Rebrandly', 'value' => self::PROVIDER_REBRANDLY],
            ['label' => 'Short.io', 'value' => self::PROVIDER_SHORTIO],
        ];
    }


    // Protected Methods
    // =========================================================================

    protected function defineRules(): array
    {
        $rules = parent::defineRules();

        $rules[] = [[
            'urlSegment',
            'customDomain',
            'provider',
            'bitlyAccessToken',
            'rebrandlyApiKey',
            'rebrandlyDomain',
            'rebrandlyWorkspaceId',
            'shortioApiKey',
            'shortioDomain',
        ], 'string'];
        $rules[] = [['hashLength'], 'integer', 'min' => 1];
        $rules[] = [['hideUrlSegment'], 'boolean'];
        $rules[] = [['provider'], 'in', 'range' => [
            self::PROVIDER_LOCAL,
            self::PROVIDER_BITLY,
            self::PROVIDER_TINYURL,
            self::PROVIDER_ISGD,
            self::PROVIDER_REBRANDLY,
            self::PROVIDER_SHORTIO,
        ]];

        return $rules;
    }
}
