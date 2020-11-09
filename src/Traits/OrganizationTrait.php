<?php

namespace EdsonAlvesan\DigiSac\Traits;

use EdsonAlvesan\DigiSac\Objects\Organization;

trait OrganizationTrait
{

    public function getOrganization(array $url_token, $id, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->get('organizations', $id, $params);

        return new Organization($response->getDecodedBody());
    }

    public function addOrganization(array $url_token, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->post('organizations', $params);

        return new Organization($response->getDecodedBody());        
    }

    public function updateOrganization(array $url_token, $id, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->put('organizations/'.$id, $params);

        return new Organization($response->getDecodedBody());        
    }

    public function deleteOrganization(array $url_token, $id)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->delete('organizations', $id);

        return new Organization($response->getDecodedBody());        
    }

}