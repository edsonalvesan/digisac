<?php

namespace EdsonAlvesan\DigiSac\Traits;

use EdsonAlvesan\DigiSac\Objects\Department;

trait DepartmentTrait
{

    public function getDepartment(array $url_token, $id, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->get('departments', $id, $params);

        return new Department($response->getDecodedBody());
    }

    public function addDepartment(array $url_token, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->post('departments', $params);

        return new Department($response->getDecodedBody());        
    }

    public function updateDepartment(array $url_token, $id, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->put('departments/'.$id, $params);

        return new Department($response->getDecodedBody());        
    }

    public function deleteDepartment(array $url_token, $id)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->delete('departments', $id);

        return new Department($response->getDecodedBody());        
    }

}