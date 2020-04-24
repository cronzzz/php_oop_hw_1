<?php

require_once BASE_CLASS_PATH . DS . 'LeaderEntity.php';

class Human extends LeaderEntity
{
    private $name = null;

    public function __construct($location, $name = '')
    {
        $this->name = $name;
        parent::__construct($location);
    }

    public function getName()
    {
        return $this->name;
    }

    public function canChangeBiomes()
    {
        return true;
    }

    public function getNoAccessErrorMessage()
    {
        return null;
    }
}
