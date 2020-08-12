<?php

namespace EdsonAlvesan\DigiSac\Traits;

use EdsonAlvesan\DigiSac\Objects\Tag;

trait TagTrait
{

    public function getTag(array $url_token, $id, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->get('tags', $id, $params);

        return new Tag($response->getDecodedBody());
    }

    public function addTag(array $url_token, $id, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->post('tags', $params);

        return new Tag($response->getDecodedBody());        
    }

    public function updateTag(array $url_token, $id, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->put('tags', $params);

        return new Tag($response->getDecodedBody());        
    }

    public function deleteTag(array $url_token, $id)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->delete('tags', $id);

        return new Tag($response->getDecodedBody());        
    }

}