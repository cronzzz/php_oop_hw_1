<?php

class InteractionManager
{
    public function execute(Closure $callable)
    {
        $callable();
    }
}
