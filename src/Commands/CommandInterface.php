<?php

namespace EdsonAlvesan\DigiSac\Commands;

interface CommandInterface
{

    public function make($digisac, $arguments, $update);


    public function handle($arguments);
}
