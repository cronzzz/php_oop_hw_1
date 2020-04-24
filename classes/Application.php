<?php

require_once BASE_CLASS_PATH . DS . 'Logger.php';
require_once BASE_CLASS_PATH . DS . 'RouteManager.php';
require_once BASE_CLASS_PATH . DS . 'TransferManager.php';
require_once BASE_CLASS_PATH . DS . 'InteractionManager.php';

class Application
{
    const SLEEP_TIME = 0;

    private static $instance = null;
    private $singletons = null;

    /**
     * @var Logger
     */
    private $logger = null;

    /**
     * @var $human Human
     */
    private $human = null;
    /**
     * @var $dog Dog
     */
    private $dog = null;

    private $biomes = null;
    /**
     * @var $routeManager RouteManager
     */
    private $routeManager = null;

    public function __construct()
    {
        $this->logger = new Logger();
        $this->singletons = [];
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get($className)
    {
        if (!isset($this->singletons[$className])) {
            $this->singletons[$className] = new $className();
        }
        return $this->singletons[$className];
    }

    public function getLogger()
    {
        return $this->logger;
    }

    private function wait()
    {
        sleep(self::SLEEP_TIME);
    }

    private function initialize($configuration)
    {
        $this->biomes = $configuration['biomes'];
        $this->human = $configuration['human'];
        $this->dog = $configuration['dog'];
        $this->routeManager = new RouteManager();
        foreach ($configuration['route'] as $item) {
            $this->routeManager->addLocationToRoute($item);
        }
    }

    private function runScenario()
    {
        $this->wait();
        $this->human->sayHello();
        $this->wait();
        $this->dog->sayHello();
        $this->wait();

        while (!$lead = $this->human->lookupLead()) {
            $this->wait();
            foreach ($this->human->getLocation()->getBiome()->getLocations() as $location) {
                if ($location == $this->human->getLocation()) {
                    continue;
                }
                $this->get(TransferManager::class)->transferEntityToLocation($this->human, $location);
                $this->wait();
            }
        }

        $this->human->pickUpLead($lead);
        $this->wait();
        if (!$this->human->attachLeadTo($this->dog)) {
            $this->wait();
            $this->human->call($this->dog);
            $this->wait();
            $this->human->attachLeadTo($this->dog);
            $this->wait();
        }

        /**
         * @var $currentLocation Location
         */
        while ($currentLocation = $this->routeManager->getNextLocation()) {
            $this->get(TransferManager::class)->transferEntityToLocation($this->human, $currentLocation);
            $this->wait();
            $this->get(TransferManager::class)->transferEntityToLocation($this->dog, $currentLocation);
            $this->wait();
            /**
             * @var $object LocationObjectInterface
             */
            foreach ($currentLocation->getObjects() as $object) {
                $this->get(InteractionManager::class)->execute($this->human->getHabit($object->getCode()));
                $this->wait();
                $this->get(InteractionManager::class)->execute($this->dog->getHabit($object->getCode()));
                $this->wait();
            }
        }
    }

    public function run($configuration)
    {
        $this->initialize($configuration);
        $this->runScenario();
    }
}
