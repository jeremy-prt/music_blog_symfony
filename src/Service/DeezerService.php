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
        if (empty($data['data'])) return null;

        $artist = $data['data'][0];
        $artistDetails = $this->client->request('GET', "https://api.deezer.com/artist/{$artist['id']}")->toArray();
        $topTracks = $this->client->request('GET', "https://api.deezer.com/artist/{$artist['id']}/top?limit=5")->toArray();
        $albums = $this->client->request('GET', "https://api.deezer.com/artist/{$artist['id']}/albums?limit=3")->toArray();

        return [
            'name' => $artistDetails['name'],
            'image' => $artistDetails['picture_xl'],
            'link' => $artistDetails['link'],
            'fans' => $artistDetails['nb_fan'],
            'hasRadio' => $artistDetails['radio'],
            'topTracks' => $topTracks['data'] ?? [],
            'albums' => $albums['data'] ?? [],
        ];
    }
}