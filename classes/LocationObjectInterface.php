<?php

interface LocationObjectInterface
{
    public function getCode();
    public function isApplicableToEntity(Entity $entity);
    public function canBeUsedByEntity(Entity $entity);
    public function useObject(Closure $callback = null);
    public function getLocation();
    public function setLocation(Location $location);
}
