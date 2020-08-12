<?php

namespace EdsonAlvesan\DigiSac\Traits;

use EdsonAlvesan\DigiSac\Objects\Message;

trait MessageTrait
{

    
  public function getService(array $url_token, $id, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->get('messages', $id, $params);

        return new Message($response->getDecodedBody());
    }

  
  /**
     * Send text messages.
     *
     * <code>
     * $url_token = [
     *   'url' => '',
     *   'token' => ''
     * ]
     * $params = [
     *   'text'                     => '',
     *   'number'                   => '',
     *   'serviceId'                => '',
     * ];
     * </code>
     *
     * @return Message
     */
    public function sendMessage(array $url_token, array $params)
    {
        if (!empty($url_token)) {
          $this->url_token = $url_token; 
        }

        $response = $this->post('messages', $params);


        return new Message($response->getDecodedBody());
    }

    public function revokeMessage(array $url_token, $id)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->post('messages/'.$id.'/revoke');

        return new Message($response->getDecodedBody());        
    }

    public function syncFileMessage(array $url_token, $id)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->post('messages/'.$id.'/sync-file');

        return new Message($response->getDecodedBody());        
    }

    public function forwardMessage(array $url_token, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->post('messages/forward');

        return new Message($response->getDecodedBody());        
    }

    public function deleteManyMessage(array $url_token, $id)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->delete('messages/many');

        return new Message($response->getDecodedBody());        
    }

}