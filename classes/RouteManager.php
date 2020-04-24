<?php

class RouteManager
{
    private $currentLocation = null;
    private $routeLocations = null;

    public function __construct()
    {
        $this->currentLocation = 0;
        $this->routeLocations = null;
    }

    public function addLocationToRoute($location)
    {
        $this->routeLocations[] = $location;
    }

    public function getNextLocation()
    {
        return (isset($this->routeLocations[$this->currentLocation])) ?
            $this->routeLocations[$this->currentLocation++] :
            null
        ;
    }
}
