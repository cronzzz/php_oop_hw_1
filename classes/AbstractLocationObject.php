<?php

require_once BASE_CLASS_PATH . DS . 'LocationObjectInterface.php';

abstract class AbstractLocationObject implements LocationObjectInterface
{
    protected $code = null;
    protected $location = null;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation(Location $location)
    {
        $this->location = $location;
    }
}
