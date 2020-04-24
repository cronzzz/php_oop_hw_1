<?php

require_once BASE_CLASS_PATH . DS . 'Entity.php';

abstract class BoundedEntity extends Entity
{
    const MESSAGE_ENTITY_NOT_BOUND = '%s is not bound';
    const MESSAGE_ENTITY_IS_BOUND = '%s is bound';
    const MESSAGE_RESPONSE_TO_LEAD = '%s joined %s in %s';

    /**
     * @var $lead Lead
     */
    private $lead = null;

    public function getLead()
    {
        return $this->lead;
    }

    public function setLead(Lead $lead)
    {
        if ($lead) {
            $this->lead = $lead;
            Application::getInstance()->getLogger()->info($this->getEntityBoundMessage());
        } else {
            Application::getInstance()->getLogger()->info($this->getEntityNotBoundErrorMessage());
        }
    }

    public function isBounded()
    {
        return !is_null($this->getLead());
    }

    public function getEntityNotBoundErrorMessage()
    {
        return sprintf(self::MESSAGE_ENTITY_NOT_BOUND, $this->getName());
    }

    public function getEntityBoundMessage()
    {
        return sprintf(self::MESSAGE_ENTITY_IS_BOUND, $this->getName());
    }

    public function isFollowingTo($location)
    {
        return $this->getLead()->getLeader()->getLocation() == $location;
    }

    public function respond(LeaderEntity $entity)
    {
        Application::getInstance()->get(TransferManager::class)->transferEntityToLocation($this, $entity->getLocation());
        Application::getInstance()->getLogger()->info(
            sprintf(
                self::MESSAGE_RESPONSE_TO_LEAD,
                $this->getName(),
                $entity->getName(),
                $entity->getLocation()->getCode()
            )
        );
    }
}
