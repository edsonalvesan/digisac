<?php

namespace EdsonAlvesan\DigiSac\Commands;

use EdsonAlvesan\DigiSac\Api;
use EdsonAlvesan\DigiSac\Objects\Update;


abstract class Command implements CommandInterface
{

    protected $name;

    protected $description;

    protected $digisac;

    protected $arguments;

    protected $update;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getDigisac()
    {
        return $this->digisac;
    }

    public function getUpdate()
    {
        return $this->update;
    }

    public function getArguments()
    {
        return $this->arguments;
    }

    public function getCommandBus()
    {
        return $this->digisac->getCommandBus();
    }

    public function make($digisac, $arguments, $update)
    {
        $this->digisac = $digisac;
        $this->arguments = $arguments;
        $this->update = $update;

        return $this->handle($arguments);
    }

    protected function triggerCommand($command, $arguments = null)
    {
        return $this->getCommandBus()->execute($command, $arguments ?: $this->arguments, $this->update);
    }

    abstract public function handle($arguments);

    public function __call($method, $arguments)
    {
        $action = substr($method, 0, 9);
        if ($action === 'replyWith') {
            $reply_name = studly_case(substr($method, 9));
            $methodName = 'send'.$reply_name;

            if (!method_exists($this->digisac, $methodName)) {
                return 'Method Not Found';
            }

            $chat_id = $this->update->getMessage()->getChat()->getId();
            $params = array_merge(compact('chat_id'), $arguments[0]);

            return call_user_func_array([$this->digisac, $methodName], [$params]);
        }

        return 'Method Not Found';
    }
}
