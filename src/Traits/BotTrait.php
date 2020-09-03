<?php

namespace EdsonAlvesan\DigiSac\Traits;

use EdsonAlvesan\DigiSac\Objects\Bot;

trait BotTrait
{

     /**
     * @return Bot
     */

    
    public function triggerSignal(array $url_token, $botId, $contatcId, $flag, array $params)
    {
        if (!empty($url_token)) {
            $this->url_token = $url_token; 
        }
        
        $response = $this->post('bots/'.$botId.'/trigger-signal/'.$contatcId.'?flag='.$flag, $params);

        return new Bot($response->getDecodedBody());  
    }
    

}