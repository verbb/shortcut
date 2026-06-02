<?php
namespace verbb\shortcut\shorteners;

use verbb\shortcut\models\Settings;

use Craft;
use craft\helpers\App;

class RebrandlyShortener implements ShortenerInterface
{
    // Public Methods
    // =========================================================================

    public function shorten(string $url): string
    {
        $apiKey = App::parseEnv($this->settings->rebrandlyApiKey);
        $domain = App::parseEnv($this->settings->rebrandlyDomain);
        $workspaceId = App::parseEnv($this->settings->rebrandlyWorkspaceId);

        if (!$apiKey) {
            throw new ShortenerException(Craft::t('shortcut', 'A Rebrandly API key is required to shorten URLs with Rebrandly.'));
        }

        $headers = [
            'Accept' => 'application/json',
            'apikey' => $apiKey,
        ];

        if ($workspaceId) {
            $headers['workspace'] = $workspaceId;
        }

        $payload = [
            'destination' => $url,
        ];

        if ($domain) {
            $payload['domain'] = [
                'fullName' => $domain,
            ];
        }

        try {
            $client = Craft::createGuzzleClient();
            $response = $client->post('https://api.rebrandly.com/v1/links', [
                'headers' => $headers,
                'json' => $payload,
                'timeout' => 10,
            ]);
        } catch (\Throwable $e) {
            throw new ShortenerException(Craft::t('shortcut', 'Could not shorten URL with Rebrandly: {message}', [
                'message' => $e->getMessage(),
            ]), 0, $e);
        }

        $data = json_decode((string)$response->getBody(), true);
        $link = $data['shortUrl'] ?? null;

        if (!$link) {
            throw new ShortenerException(Craft::t('shortcut', 'Rebrandly did not return a shortened URL.'));
        }

        if (!str_starts_with($link, 'http')) {
            $link = 'https://' . ltrim($link, '/');
        }

        return $link;
    }
}
