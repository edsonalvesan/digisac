<?php

namespace EdsonAlvesan\DigiSac\Traits;

use EdsonAlvesan\DigiSac\Objects\Person;

trait PersonTrait
{

    public function getPerson(array $url_token, $id, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->get('people', $id, $params);

        return new Person($response->getDecodedBody());
    }

    public function addPerson(array $url_token, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->post('people', $params);

        return new Person($response->getDecodedBody());        
    }

    public function updatePerson(array $url_token, $id, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->put('people/'.$id, $params);

        return new Person($response->getDecodedBody());        
    }

    public function deletePerson(array $url_token, $id)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->delete('people', $id);

        return new Person($response->getDecodedBody());        
    }

}