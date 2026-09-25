<?php

namespace App\Support;

class YouTubeVideo
{
    public static function embedUrl(?string $url): ?string
    {
        if (! is_string($url) || ! filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        $parts = parse_url($url);
        if (! is_array($parts) || ! in_array(strtolower($parts['scheme'] ?? ''), ['http', 'https'], true)) {
            return null;
        }

        $host = strtolower($parts['host'] ?? '');
        $path = $parts['path'] ?? '';
        $id = null;

        if ($host === 'youtu.be' || $host === 'www.youtu.be') {
            $id = trim($path, '/');
        } elseif (in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com'], true)) {
            if ($path === '/watch') {
                parse_str($parts['query'] ?? '', $query);
                $id = $query['v'] ?? null;
            } elseif (preg_match('~^/(?:shorts|embed|live)/([^/]+)/?$~', $path, $matches)) {
                $id = $matches[1];
            }
        }

        return is_string($id) && preg_match('/\A[A-Za-z0-9_-]{11}\z/', $id)
            ? 'https://www.youtube-nocookie.com/embed/'.$id
            : null;
    }
}
