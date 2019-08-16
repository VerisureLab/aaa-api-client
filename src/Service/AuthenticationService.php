<?php

namespace VerisureLab\Library\AAAApiClient\Service;

use GuzzleHttp\Exception\GuzzleException;
use VerisureLab\Library\AAAApiClient\Exception\ClientRequestException;
use VerisureLab\Library\AAAApiClient\ValueObject\Token;

class AuthenticationService
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get token from username and password
     *
     * @param string $username
     * @param string $password
     *
     * @return Token
     *
     * @throws ClientRequestException
     * @throws GuzzleException
     * @throws \Exception
     */
    public function authenticate(string $username, string $password): Token
    {
        return Token::fromAuthenticationResponse($this->client->obtainToken($username, $password));
    }

    /**
     * Get a new token with the refresh token
     *
     * @param string $refreshToken
     *
     * @return Token
     *
     * @throws ClientRequestException
     * @throws GuzzleException
     * @throws \Exception
     */
    public function refreshToken(string $refreshToken): Token
    {
        return Token::fromAuthenticationResponse($this->client->refreshToken($refreshToken));
    }
}