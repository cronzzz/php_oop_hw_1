<?php

require_once BASE_CLASS_PATH . DS . 'BoundedEntity.php';

class Dog extends BoundedEntity
{
    const MESSAGE_NO_ACCESS = '%s cant go there';

    private $nicknames = null;

    public function __construct($location)
    {
        $this->nicknames = [];
        parent::__construct($location);
    }

    public function addNickname($nickname)
    {
        $this->nicknames[] = $nickname;
    }

    public function getNoAccessErrorMessage()
    {
        return sprintf(self::MESSAGE_NO_ACCESS, $this->getName());
    }

    public function getName()
    {
        return $this->nicknames[array_rand($this->nicknames)];
    }

    public function canChangeBioms()
    {
        return false;
    }

}