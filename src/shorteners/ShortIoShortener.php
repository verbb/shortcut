<?php
namespace verbb\shortcut\shorteners;

use verbb\shortcut\models\Settings;

use Craft;
use craft\helpers\App;

class ShortIoShortener implements ShortenerInterface
{
    // Public Methods
    // =========================================================================

    public function shorten(string $url): string
    {
        $apiKey = App::parseEnv($this->settings->shortioApiKey);
        $domain = App::parseEnv($this->settings->shortioDomain);

        if (!$apiKey) {
            throw new ShortenerException(Craft::t('shortcut', 'A Short.io API key is required to shorten URLs with Short.io.'));
        }

        if (!$domain) {
            throw new ShortenerException(Craft::t('shortcut', 'A Short.io domain is required to shorten URLs with Short.io.'));
        }

        try {
            $client = Craft::createGuzzleClient();
            $response = $client->post('https://api.short.io/links', [
                'headers' => [
                    'Authorization' => $apiKey,
                    'Accept' => 'application/json',
                ],
                'json' => [
                    'allowDuplicates' => false,
                    'domain' => $domain,
                    'originalURL' => $url,
                ],
                'timeout' => 10,
            ]);
        } catch (\Throwable $e) {
            throw new ShortenerException(Craft::t('shortcut', 'Could not shorten URL with Short.io: {message}', [
                'message' => $e->getMessage(),
            ]), 0, $e);
        }

        $data = json_decode((string)$response->getBody(), true);
        $link = $data['shortURL'] ?? null;

        if (!$link) {
            throw new ShortenerException(Craft::t('shortcut', 'Short.io did not return a shortened URL.'));
        }

        return $link;
    }
}
