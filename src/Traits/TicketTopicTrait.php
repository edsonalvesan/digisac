<?php

namespace EdsonAlvesan\DigiSac\Traits;

use EdsonAlvesan\DigiSac\Objects\TicketTopic;

trait TicketTopicTrait
{

    public function getTicketTopic(array $url_token, $id, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->get('ticket-topics', $id, $params);

        return new TicketTopic($response->getDecodedBody());
    }

    public function addTicketTopic(array $url_token, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->post('ticket-topics', $params);

        return new TicketTopic($response->getDecodedBody());        
    }

    public function updateTicketTopic(array $url_token, $id, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->put('ticket-topics/'.$id, $params);

        return new TicketTopic($response->getDecodedBody());        
    }

    public function deleteTicketTopic(array $url_token, $id)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->delete('ticket-topics', $id);

        return new TicketTopic($response->getDecodedBody());        
    }

}