<?php
namespace verbb\shortcut\shorteners;

use verbb\shortcut\models\Settings;

use Craft;
use craft\helpers\App;

class BitlyShortener implements ShortenerInterface
{
    // Public Methods
    // =========================================================================

    public function shorten(string $url): string
    {
        $accessToken = App::parseEnv($this->settings->bitlyAccessToken);

        if (!$accessToken) {
            throw new ShortenerException(Craft::t('shortcut', 'A Bitly access token is required to shorten URLs with Bitly.'));
        }

        try {
            $client = Craft::createGuzzleClient();
            $response = $client->post('https://api-ssl.bitly.com/v4/shorten', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept' => 'application/json',
                ],
                'json' => [
                    'long_url' => $url,
                ],
                'timeout' => 10,
            ]);
        } catch (\Throwable $e) {
            throw new ShortenerException(Craft::t('shortcut', 'Could not shorten URL with Bitly: {message}', [
                'message' => $e->getMessage(),
            ]), 0, $e);
        }

        $data = json_decode((string)$response->getBody(), true);
        $link = $data['link'] ?? null;

        if (!$link) {
            throw new ShortenerException(Craft::t('shortcut', 'Bitly did not return a shortened URL.'));
        }

        return $link;
    }
}
