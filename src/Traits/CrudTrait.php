<?php

namespace EdsonAlvesan\DigiSac\Traits;

trait CrudTrait
{

    protected $route = null;
    protected $objectClass = null;

    public function index(array $url_token, $id, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->get($this->route, $id, $params);

        return $this->response($response->getDecodedBody());
    }

    public function store(array $url_token, $id, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->post($this->route, $params);

        return $this->response($response->getDecodedBody());        
    }

    public function update(array $url_token, $id, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->put($this->route, $params);

        return $this->response($response->getDecodedBody());        
    }

    public function delete(array $url_token, $id)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->delete($this->route, $id);

        return $this->response($response->getDecodedBody());        
    }

    protected function response($data)
    {
        if (!is_string($this->objectClass)) {
            throw new \Exception('class not set.');
        }

        return new $this->objectClass($data);
    }

}