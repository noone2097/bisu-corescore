<?php

namespace App\Guards;

use App\Models\AdminAccounts;
use Illuminate\Auth\SessionGuard;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Cookie;

class AdminAccountGuard extends SessionGuard
{
    public function attempt(array $credentials = [], $remember = false)
    {
        // Find user by email
        $user = AdminAccounts::where('email', $credentials['email'])->first();

        if (!$user) {
            return false;
        }

        // Check if password matches
        if (!Hash::check($credentials['password'], $user->password)) {
            return false;
        }

        // Check if account is active
        if ($user->status !== 'active') {
            throw new \Exception('Your account is inactive. Please contact the administrator.');
        }

        // Log the user in
        $this->login($user, $remember);

        return true;
    }

    public function validate(array $credentials = [])
    {
        $user = AdminAccounts::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return false;
        }

        // Check if account is active
        if ($user->status !== 'active') {
            throw new \Exception('Your account is inactive. Please contact the administrator.');
        }

        return true;
    }

    /**
     * Get the currently authenticated user.
     */
    public function user()
    {
        if ($this->loggedOut) {
            return null;
        }

        // If we've already retrieved the user for the current request we can just
        // return it back immediately. We do not want to fetch the user data on
        // every call to this method because that would be tremendously slow.
        if (!is_null($this->user)) {
            return $this->user;
        }

        $id = $this->session->get($this->getName());

        if (!is_null($id)) {
            $user = $this->provider->retrieveById($id);

            // Check if account is still active
            if ($user instanceof AdminAccounts && $user->status !== 'active') {
                $this->logout();
                throw new \Exception('Your account has been deactivated.');
            }

            $this->user = $user;
        }

        return $this->user;
    }

    /**
     * Log the user out of the application.
     *
     * @return void
     */
    public function logout()
    {
        $this->clearUserDataFromStorage();

        $this->user = null;
        $this->loggedOut = true;

        // Properly handle cookie removal during logout
        if ($this->session) {
            $this->session->flush();
            $this->session->regenerate(true);
        }
    }

    /**
     * Remove the user data from the session and cookies.
     *
     * @return void
     */
    protected function clearUserDataFromStorage()
    {
        parent::clearUserDataFromStorage();
        
        // Clear any remember-me cookies
        Cookie::queue(Cookie::forget($this->getRecallerName()));
    }
}