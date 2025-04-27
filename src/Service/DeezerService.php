<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class DeezerService
{
    public function __construct(private HttpClientInterface $client) {}

    public function getArtistInfo(string $name): ?array
    {
        $response = $this->client->request('GET', 'https://api.deezer.com/search/artist', [
            'query' => ['q' => $name],
        ]);

        $data = $response->toArray();

        if (empty($data['data'])) {
            return null;
        }

        $artist = $data['data'][0];

        $topTracks = $this->client->request('GET', "https://api.deezer.com/artist/{$artist['id']}/top?limit=5")
            ->toArray();

        return [
            'name' => $artist['name'],
            'link' => $artist['link'],
            'image' => $artist['picture_xl'],
            'topTracks' => $topTracks['data'] ?? [],
        ];
    }
}