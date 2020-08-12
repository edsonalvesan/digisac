<?php

namespace EdsonAlvesan\DigiSac\Traits;

use EdsonAlvesan\DigiSac\Objects\User;

trait UserTrait
{

    /**
     * A simple method for testing your bot's auth token.
     * Returns basic information about the bot in form of a User object.
     *
     * @return User
     */
    public function getMe()
    {
        $response = $this->post('getMe');

        return new User($response->getDecodedBody());
    }

    public function getUser(array $url_token, $id, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->get('users', $id, $params);

        return new User($response->getDecodedBody());
    }

    public function addUser(array $url_token, $id, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->post('users', $params);

        return new User($response->getDecodedBody());        
    }

    public function updateUser(array $url_token, $id, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->put('users', $params);

        return new User($response->getDecodedBody());        
    }

    public function deleteUser(array $url_token, $id)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->delete('users', $id);

        return new User($response->getDecodedBody());        
    }

}