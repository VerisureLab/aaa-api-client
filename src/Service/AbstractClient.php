<?php

namespace VerisureLab\Library\AAAApiClient\Service;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use VerisureLab\Library\AAAApiClient\Exception\ClientRequestException;

abstract class AbstractClient
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    public function __construct(string $baseUri)
    {
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => $baseUri,
            'timeout' => 5,
            'http_errors' => true,
        ]);
    }

    /**
     * Execute the request
     *
     * @param string $method
     * @param string $uri
     * @param array $params
     *
     * @return array
     *
     * @throws ClientRequestException
     * @throws GuzzleException
     */
    protected function handleRequest(string $method, string $uri, array $params): array
    {
        try {
            $response = $this->client->request($method, $uri, $params);
        } catch (RequestException $e) {
            throw new ClientRequestException(Psr7\str($e->getRequest()), $e->getCode(), $e);
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}