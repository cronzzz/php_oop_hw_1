<?php



abstract class Entity
{
    const MESSAGE_HELLO = 'Hello, App, I\'m %s and I\'m in %s';

    /**
     * @var Location
     */
    protected $location = null;

    /**
     * @var Closure[]
     */
    private $habits = null;

    public function __construct($location)
    {
        $this->location = $location;
        $this->habits = [];
    }

    /**
     * @param $location
     * @return Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($location)
    {
        $this->location = $location;
    }

    public function sayHello()
    {
        Application::getInstance()->getLogger()->info(
            sprintf(
                self::MESSAGE_HELLO,
                $this->getName(),
                $this->getLocation()->getCode()
            )
        );
    }

    abstract public function getName();
    abstract public function canChangeBioms();
    abstract public function getNoAccessErrorMessage();
    public function addHabit(string $objectCode, Closure $callback)
    {
        $this->habits[$objectCode] = $callback;
    }

    public function getHabit(string $objectCode)
    {
        return isset($this->habits[$objectCode]) ? $this->habits[$objectCode] : function() {};
    }

    public function interactWithObject(LocationObjectInterface $object)
    {
        $object->useObject($this->getHabit($object->getCode()));
    }
}
