<?php
namespace verbb\shortcut\models;

use craft\base\Model;

class Settings extends Model
{
    // Properties
    // =========================================================================

    public string $urlSegment = 's';
    public bool $hideUrlSegment = false;
    public string $customDomain = '';
    public int $hashLength = 12;
}
