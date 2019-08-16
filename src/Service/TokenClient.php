<?php

namespace VerisureLab\Library\AAAApiClient\Service;

use GuzzleHttp\Exception\GuzzleException;
use VerisureLab\Library\AAAApiClient\Exception\ClientRequestException;

class TokenClient extends AbstractClient
{
    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $clientSecret;

    public function __construct(string $clientId, string $clientSecret, string $baseUri)
    {
        parent::__construct($baseUri);

        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
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
}