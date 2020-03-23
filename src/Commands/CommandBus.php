<?php

namespace EdsonAlvesan\DigiSac\Commands;

use EdsonAlvesan\DigiSac\Api;
use EdsonAlvesan\DigiSac\Exceptions\DigiSacSDKException;
use EdsonAlvesan\DigiSac\Objects\Update;


class CommandBus
{
  
    protected $commands = [];

    private $digisac;

    public function __construct(Api $digisac)
    {
        $this->digisac = $digisac;
    }


    public function getCommands()
    {
        return $this->commands;
    }

    public function addCommands(array $commands)
    {
        foreach ($commands as $command) {
            $this->addCommand($command);
        }

        return $this;
    }


    public function addCommand($command)
    {
        if (!is_object($command)) {
            if (!class_exists($command)) {
                throw new DigiSacSDKException(
                    sprintf(
                        'Command class "%s" not found! Please make sure the class exists.',
                        $command
                    )
                );
            }

            if ($this->digisac->hasContainer()) {
                $command = $this->buildDependencyInjectedCommand($command);
            } else {
                $command = new $command();
            }
        }

        if ($command instanceof CommandInterface) {

      
            $this->commands[$command->getName()] = $command;

            return $this;
        }

        throw new DigiSacSDKException(
            sprintf(
                'Command class "%s" should be an instance of "Telegram\Bot\Commands\CommandInterface"',
                get_class($command)
            )
        );
    }

 
    public function removeCommand($name)
    {
        unset($this->commands[$name]);

        return $this;
    }


    public function removeCommands(array $names)
    {
        foreach ($names as $name) {
            $this->removeCommand($name);
        }

        return $this;
    }

    public function handler($message, Update $update)
    {
        $match = $this->parseCommand($message);
        if (!empty($match)) {
            $command = $match[1];
            $arguments = $match[3];
            $this->execute($command, $arguments, $update);
        }

        return $update;
    }

    public function parseCommand($text)
    {
        if (trim($text) === '') {
            throw new \InvalidArgumentException('Message is empty, Cannot parse for command');
        }

        preg_match('/^\/([^\s@]+)@?(\S+)?\s?(.*)$/', $text, $matches);

        return $matches;
    }

    public function execute($name, $arguments, $message)
    {
        if (array_key_exists($name, $this->commands)) {
            return $this->commands[$name]->make($this->telegram, $arguments, $message);
        } elseif (array_key_exists('help', $this->commands)) {
            return $this->commands['help']->make($this->telegram, $arguments, $message);
        }

        return 'Ok';
    }

    private function buildDependencyInjectedCommand($commandClass)
    {

        // check if the command has a constructor
        if (!method_exists($commandClass, '__construct')) {
            return new $commandClass();
        }

        // get constructor params
        $constructorReflector = new \ReflectionMethod($commandClass, '__construct');
        $params = $constructorReflector->getParameters();

        // if no params are needed proceed with normal instantiation
        if (empty($params)) {
            return new $commandClass();
        }

        // otherwise fetch each dependency out of the container
        $container = $this->digisac->getContainer();
        $dependencies = [];
        foreach ($params as $param) {
            $dependencies[] = $container->make($param->getClass()->name);
        }

        // and instantiate the object with dependencies through ReflectionClass
        $classReflector = new \ReflectionClass($commandClass);

        return $classReflector->newInstanceArgs($dependencies);
    }
}
