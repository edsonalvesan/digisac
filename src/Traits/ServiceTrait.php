<?php

namespace EdsonAlvesan\DigiSac\Traits;

use EdsonAlvesan\DigiSac\Objects\Service;

trait ServiceTrait
{

    /**
     * A simple method for testing service.
     * Returns basic information Service object.
     * $ id = '' // Rest
     * $params = [
     *  
     *  ];
     *
     * @return Service
     */
    public function getService(array $url_token, $id, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->get('services', $id, $params);

        return new Service($response->getDecodedBody());
    }

    public function addService(array $url_token, $id, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->post('services', $params);

        return new Service($response->getDecodedBody());        
    }

    public function updateService(array $url_token, $id, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->put('services', $params);

        return new Service($response->getDecodedBody());        
    }

    public function deleteService(array $url_token, $id)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->delete('services', $id);

        return new Service($response->getDecodedBody());        
    }

    public function screenshotService(array $url_token, $id, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->get('services/'.$id.'/screenshot', $id, $params);

        return new Service($response->getDecodedBody());
    }

    public function takeoverService(array $url_token, $id)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->post('services/'.$id.'/takeover');

        return new Service($response->getDecodedBody());        
    }

    public function shutdownService(array $url_token, $id)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->post('services/'.$id.'/shutdown');

        return new Service($response->getDecodedBody());        
    }

    public function restartService(array $url_token, $id)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->post('services/'.$id.'/restart');

        return new Service($response->getDecodedBody());        
    }

    public function logoutService(array $url_token, $id)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->post('services/'.$id.'/logout');

        return new Service($response->getDecodedBody());        
    }




}