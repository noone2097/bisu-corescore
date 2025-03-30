<?php

namespace App\Traits;

/**
 * A simplified version of the HasSocialiteConnections trait
 * to replace the missing DutchCodingCompany\FilamentSocialite\Core\HasSocialiteConnections trait
 */
trait HasSocialiteConnections
{
    /**
     * Get the user's OAuth provider
     */
    public function getProvider(): ?string
    {
        return $this->provider;
    }

    /**
     * Get the user's OAuth provider ID
     */
    public function getProviderId(): ?string
    {
        return $this->provider_id;
    }

    /**
     * Get the user's OAuth provider token
     */
    public function getProviderToken(): ?string
    {
        return $this->provider_token;
    }

    /**
     * Get the user's OAuth provider refresh token
     */
    public function getProviderRefreshToken(): ?string
    {
        return $this->provider_refresh_token;
    }

    /**
     * Determine if the user has connected with a specific provider
     */
    public function hasConnectedWith(string $provider): bool
    {
        return $this->provider === $provider && $this->provider_id !== null;
    }
}
