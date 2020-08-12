<?php

namespace EdsonAlvesan\DigiSac\Traits;

use EdsonAlvesan\DigiSac\Objects\Group;

trait GroupTrait
{

    public function getGroups(array $url_token, $id, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->get('users', $id, $params);

        return new Group($response->getDecodedBody());
    }

    public function createGroup(array $url_token, $id, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->post('users', $params);

        return new Group($response->getDecodedBody());        
    }

    public function getGroupParticipants(array $url_token, $id, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->get('users', $id, $params);

        return new Group($response->getDecodedBody());
    }

    public function addGroupParticipant(array $url_token, $id, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->post('users', $params);

        return new Group($response->getDecodedBody());        
    }

    public function removeGroupParticipant(array $url_token, $id)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->delete('users', $id);

        return new Group($response->getDecodedBody());        
    }

    public function makeGroupAdmin(array $url_token, $id)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->delete('users', $id);

        return new Group($response->getDecodedBody());        
    }

    public function removeGroupAdmin(array $url_token, $id)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->delete('users', $id);

        return new Group($response->getDecodedBody());        
    }

}