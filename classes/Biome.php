<?php

require_once 'Location.php';

class Biome
{
    private $code = null;
    private $locations = null;

    public function __construct($code)
    {
        $this->code = $code;
        $this->locations = [];
    }

    public function addLocation(Location $location)
    {
        $this->locations[$location->getCode()] = $location;
        $location->setBiome($this);
    }

    public function getLocations()
    {
        return $this->locations;
    }
}
