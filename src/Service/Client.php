<?php

namespace VerisureLab\Library\AAAApiClient\Service;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use VerisureLab\Library\AAAApiClient\Exception\ClientRequestException;

class Client
{
    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $clientSecret;

    /**
     * @var Client
     */
    private $client;

    public function __construct(string $clientId, string $clientSecret, string $baseUri)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;

        $this->client = new GuzzleClient([
            'base_uri' => $baseUri,
            'timeout' => 5,
            'http_errors' => true,
        ]);
    }

    /**
     * Retrieve token
     *
     * @param string $username
     * @param string $password
     *
     * @return array
     *
     * @throws ClientRequestException
     * @throws GuzzleException
     */
    public function obtainToken(string $username, string $password): array
    {
        return $this->handleRequest('POST', '/token', [
            \GuzzleHttp\RequestOptions::FORM_PARAMS => [
                'grant_type' => 'password',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'username' => $username,
                'password' => $password,
            ]
        ]);
    }

    /**
     * Refresh token
     *
     * @param string $refreshToken
     *
     * @return array
     *
     * @throws ClientRequestException
     * @throws GuzzleException
     */
    public function refreshToken(string $refreshToken): array
    {
        return $this->handleRequest('POST', '/token', [
            \GuzzleHttp\RequestOptions::FORM_PARAMS => [
                'grant_type' => 'refresh_token',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'refresh_token' => $refreshToken,
            ]
        ]);
    }

    /**
     * Get info token
     *
     * @param string $token
     *
     * @return array
     *
     * @throws ClientRequestException
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
    private function handleRequest(string $method, string $uri, array $params): array
    {
        try {
            $response = $this->client->request($method, $uri, $params);
        } catch (RequestException $e) {
            throw new ClientRequestException(Psr7\str($e->getRequest()), $e->getCode(), $e);
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}