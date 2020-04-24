<?php

require_once BASE_CLASS_PATH . DS . 'Location.php';
require_once BASE_CLASS_PATH . DS . 'Entity.php';
require_once BASE_CLASS_PATH . DS . 'BoundedEntity.php';

class TransferManager
{
    const MESSAGE_SUCCESS = '%s changed location from %s to %s';

    public function transferEntityToLocation(Entity $entity, Location $newLocation)
    {
        if (
            (($entity->getLocation()->getBiome() != $newLocation->getBiome()) && !$entity->canChangeBioms()) &&
            (($entity instanceof BoundedEntity) && ($entity->isBounded()) && !$entity->isFollowingTo($newLocation))
        ) {
            Application::getInstance()->getLogger()->info($entity->getNoAccessErrorMessage());
            return false;
        }
        Application::getInstance()->getLogger()->info(
            sprintf(
                self::MESSAGE_SUCCESS,
                $entity->getName(),
                $entity->getLocation()->getCode(),
                $newLocation->getCode()
            )
        );
        $entity->setLocation($newLocation);
    }
}
