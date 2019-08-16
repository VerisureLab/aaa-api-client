<?php

namespace VerisureLab\Library\AAAApiClient\ValueObject;

class Token
{
    private const SECURITY_THRESHOLD_TIME_EXPIRATION = 60 * 5;

    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var string
     */
    private $refreshToken;

    /**
     * @var \DateTime
     */
    private $expiresAt;

    private function __construct(string $accessToken, string $refreshToken, \DateTime $expireAt)
    {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->expiresAt = $expireAt;
    }

    /**
     * Factory from response
     *
     * @param array $response
     * @return Token
     *
     * @throws \Exception
     */
    public static function fromAuthenticationResponse(array $response): self
    {
        $expiresAt = new \DateTime();
        $expiresAt->add(\DateInterval::createFromDateString(sprintf('%d seconds', $response['expires_in'] - self::SECURITY_THRESHOLD_TIME_EXPIRATION)));

        return new static(
            $response['access_token'],
            $response['refresh_token'],
            $expiresAt
        );
    }

    /**
     * Factory from explicit data
     *
     * @param string $accessToken
     * @param string $refreshToken
     * @param \DateTime $expireAt
     *
     * @return Token
     */
    public static function fromExplicitData(string $accessToken, string $refreshToken, \DateTime $expireAt): self
    {
        return new static($accessToken, $refreshToken, $expireAt);
    }

    /**
     * Get accessToken
     *
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * Get refreshToken
     *
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    /**
     * Get expireAt
     *
     * @return \DateTime
     */
    public function getExpiresAt(): \DateTime
    {
        return $this->expiresAt;
    }
}