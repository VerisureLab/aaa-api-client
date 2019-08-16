<?php

namespace VerisureLab\Library\AAAApiClient\Service;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use VerisureLab\Library\AAAApiClient\Exception\ClientRequestException;

class ApiClient extends AbstractClient
{
    /**
     * Get info token
     *
     * @param string $token
     *
     * @return array
     *
     * @throws ClientRequestException
     * @throws RequestException
     * @throws GuzzleException
     */
    public function info(string $token): array
    {
        return $this->handleRequest('GET', '/info', [
            \GuzzleHttp\RequestOptions::HEADERS => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);
    }
}