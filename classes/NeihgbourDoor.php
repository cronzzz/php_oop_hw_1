<?php

class NeihgbourDoor extends AbstractLocationObject implements LocationObjectInterface
{
    const OBJECT_CODE = 'neighbour_door';

    public function canBeUsedByEntity(Entity $entity)
    {
        return true;
    }

    public function isApplicableToEntity(Entity $entity)
    {
        return false;
    }

}