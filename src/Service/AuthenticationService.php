<?php

namespace VerisureLab\Library\AAAApiClient\Service;

use GuzzleHttp\Exception\GuzzleException;
use VerisureLab\Library\AAAApiClient\Exception\ClientRequestException;
use VerisureLab\Library\AAAApiClient\ValueObject\Token;

class AuthenticationService
{
    /**
     * @var TokenClient
     */
    private $tokenClient;

    public function __construct(TokenClient $tokenClient)
    {
        $this->tokenClient = $tokenClient;
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
        return Token::fromAuthenticationResponse($this->tokenClient->obtainToken($username, $password));
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
        return Token::fromAuthenticationResponse($this->tokenClient->refreshToken($refreshToken));
    }
}