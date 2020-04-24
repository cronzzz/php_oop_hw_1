<?php

require_once BASE_CLASS_PATH . DS . 'Dog.php';

class Tree extends AbstractLocationObject implements LocationObjectInterface
{
    const OBJECT_CODE = 'tree';

    public function isApplicableToEntity(Entity $entity)
    {
        return false;
    }

    public function canBeUsedByEntity(Entity $entity)
    {
        return true;
    }

}
