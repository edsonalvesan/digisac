<?php

namespace EdsonAlvesan\DigiSac\Traits;

use EdsonAlvesan\DigiSac\Objects\Contact;

trait ContactTrait
{

     /**
     * @return Contact
     */

    public function getContact(array $url_token, $id, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->get('contacts', $id, $params);

        return new Contact($response->getDecodedBody());        
    }

    public function addContact(array $url_token, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->post('contacts', $params);

        return new Contact($response->getDecodedBody());        
    }

    public function updateContact(array $url_token, $id, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->put('contacts/'.$id, $params);

        return new Contact($response->getDecodedBody());        
    }

    public function deleteContact(array $url_token, $id)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->delete('contacts', $id);

        return new Contact($response->getDecodedBody());        
    }

    public function contactExists(array $url_token, $id, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->post('contacts', $params);

        return new Contact($response->getDecodedBody());        
    }

}