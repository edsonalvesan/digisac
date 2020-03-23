<?php

namespace EdsonAlvesan\DigiSac\Commands;


class HelpCommand extends Command
{
 
    protected $name = 'help';


    protected $description = 'Help command, Get a list of commands';

    public function handle($arguments)
    {
        $commands = $this->telegram->getCommands();

        $text = '';
        foreach ($commands as $name => $handler) {
            $text .= sprintf('/%s - %s'.PHP_EOL, $name, $handler->getDescription());
        }

        $this->replyWithMessage(compact('text'));
    }
}
