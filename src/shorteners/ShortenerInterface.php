<?php
namespace verbb\shortcut\shorteners;

interface ShortenerInterface
{
    // Public Methods
    // =========================================================================

    public function shorten(string $url): string;
}
