<?php

namespace App\Interfaces;

interface SocialiteUser
{
    /**
     * Get the redirect URL after successful social authentication.
     *
     * @return string
     */
    public function getSocialiteRedirectUrl(): string;

    /**
     * Get the authentication guard for the user.
     *
     * @return string
     */
    public function getSocialiteGuard(): string;
}