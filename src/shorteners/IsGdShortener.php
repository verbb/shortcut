<?php
namespace verbb\shortcut\shorteners;

use Craft;

class IsGdShortener implements ShortenerInterface
{
    // Public Methods
    // =========================================================================

    public function shorten(string $url): string
    {
        try {
            $client = Craft::createGuzzleClient();
            $response = $client->get('https://is.gd/create.php', [
                'query' => [
                    'format' => 'simple',
                    'url' => $url,
                ],
                'timeout' => 10,
            ]);
        } catch (\Throwable $e) {
            throw new ShortenerException(Craft::t('shortcut', 'Could not shorten URL with is.gd: {message}', [
                'message' => $e->getMessage(),
            ]), 0, $e);
        }

        $link = trim((string)$response->getBody());

        if (!str_starts_with($link, 'http')) {
            throw new ShortenerException(Craft::t('shortcut', 'is.gd did not return a shortened URL.'));
        }

        return $link;
    }
}
