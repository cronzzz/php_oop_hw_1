<?php

require_once BASE_CLASS_PATH . DS . 'Entity.php';
require_once BASE_CLASS_PATH . DS . 'Lead.php';

abstract class LeaderEntity extends Entity
{
    const MESSAGE_NO_LEAD = '%s have no lead';
    const MESSAGE_BINDING_ENTITY = 'Binding %s';
    const MESSAGE_TRY_ATTACH_LEAD = '%s trying to attach lead to %s';
    const MESSAGE_DRIVEN_ENTITY_NOT_FOUND = '%s not here';
    const MESSAGE_CALLING_FOR_BOUNDED = '%s calling for %s';
    const MESSAGE_LOOKING_FOR_LEAD = '%s looking for lead';

    /**
     * @var $lead Lead
     */
    private $lead = null;

    public function pickUpLead(Lead $lead)
    {
        $this->lead = $lead;
    }

    public function lookupLead()
    {
        Application::getInstance()->getLogger()->info(sprintf(self::MESSAGE_LOOKING_FOR_LEAD, $this->getName()));
        return $this->location->lookupObject(Lead::OBJECT_CODE);
    }

    public function getLead()
    {
        return $this->lead;
    }

    public function attachLeadTo(BoundedEntity $entity)
    {
        Application::getInstance()->getLogger()->info(sprintf(self::MESSAGE_TRY_ATTACH_LEAD, $this->getName(), $entity->getName()));
        if ($entity->getLocation() != $this->getLocation()) {
            Application::getInstance()->getLogger()->info(sprintf(self::MESSAGE_DRIVEN_ENTITY_NOT_FOUND, $entity->getName()));
            return false;
        }

        if ($this->getLead()) {
            Application::getInstance()->getLogger()->info($this->getBindingMessage($entity));
            $lead = $this->getLead();
            $leader = $this;
            $this->getLead()->useObject(function() use ($lead, $leader, $entity) {
                $lead->setLeader($leader);
                $entity->setLead($lead);
                $lead->setDriven($entity);
            });
            return true;
        } else {
            Application::getInstance()->getLogger()->info($this->getNoLeadErrorMessage());
            return false;
        }
    }

    public function getNoLeadErrorMessage()
    {
        return sprintf(self::MESSAGE_NO_LEAD, $this->getName());
    }

    public function getBindingMessage(BoundedEntity $bindingEntity)
    {
        return sprintf(self::MESSAGE_BINDING_ENTITY, $bindingEntity->getName());
    }

    public function call(BoundedEntity $entity)
    {
        Application::getInstance()->getLogger()->info(sprintf(self::MESSAGE_CALLING_FOR_BOUNDED, $this->getName(), $entity->getName()));
        $entity->respond($this);
    }
}
