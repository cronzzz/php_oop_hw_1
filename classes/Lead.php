<?php

require_once BASE_CLASS_PATH . DS . 'AbstractLocationObject.php';

class Lead extends AbstractLocationObject implements LocationObjectInterface
{
    const OBJECT_CODE = 'lead';

    /**
     * @var $leader LeaderEntity
     */
    private $leader = null;
    /**
     * @var $driven BoundedEntity
     */
    private $driven = null;

    public function isApplicableToEntity(Entity $entity)
    {
        return $entity instanceof BoundedEntity;
    }

    public function canBeUsedByEntity(Entity $entity)
    {
        return $entity instanceof LeaderEntity;
    }

    /**
     * @return LeaderEntity
     */
    public function getLeader()
    {
        return $this->leader;
    }

    /**
     * @param LeaderEntity $leader
     */
    public function setLeader($leader)
    {
        $this->leader = $leader;
    }

    /**
     * @return BoundedEntity
     */
    public function getDriven()
    {
        return $this->driven;
    }

    /**
     * @param BoundedEntity $driven
     */
    public function setDriven($driven)
    {
        $this->driven = $driven;
    }


}
