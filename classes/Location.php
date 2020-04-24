<?php

/**
 * This class possibly will contains coordinates and anything else
 */
class Location
{
    const MESSAGE_OBJECT_FOUND = '%s found in %s';
    const MESSAGE_OBJECT_NOT_FOUND = '%s not found in %s';

    /**
     * @var $biome Biome
     */
    private $biome = null;
    /**
     * @var $code string
     */
    private $code = null;
    /**
     * @var $objects LocationObject[]
     */
    private $objects = null;

    public function __construct($code)
    {
        $this->code = $code;
        $this->objects = [];
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setBiome(Biome $biome)
    {
        $this->biome = $biome;
    }

    /**
     * @return Biome|null
     */
    public function getBiome()
    {
        return $this->biome;
    }

    public function addObject(AbstractLocationObject $object)
    {
        $this->objects[] = $object;
        $object->setLocation($this);
    }

    public function getObjects()
    {
        return $this->objects;
    }

    public function lookupObject($code)
    {
        /**
         * @var $object LocationObjectInterface
         */
        foreach ($this->getObjects() as $object) {
            if ($object->getCode() == $code) {
                Application::getInstance()->getLogger()->info(sprintf(self::MESSAGE_OBJECT_FOUND, $code, $this->getCode()));
                return $object;
            }
        }
        Application::getInstance()->getLogger()->info(sprintf(self::MESSAGE_OBJECT_NOT_FOUND, $code, $this->getCode()));
        return null;
    }
}
