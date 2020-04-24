<?php

require_once BASE_CLASS_PATH . DS . 'Logger.php';
require_once BASE_CLASS_PATH . DS . 'RouteManager.php';
require_once BASE_CLASS_PATH . DS . 'TransferManager.php';

class Application
{
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
        $this->human->sayHello();
        $this->dog->sayHello();

        while (!$lead = $this->human->lookupLead()) {
            foreach ($this->human->getLocation()->getBiome()->getLocations() as $location) {
                if ($location == $this->human->getLocation()) {
                    continue;
                }
                $this->get(TransferManager::class)->transferEntityToLocation($this->human, $location);
            }
        }

        $this->human->pickUpLead($lead);
        if (!$this->human->attachLeadTo($this->dog)) {
            $this->human->call($this->dog);
            $this->human->attachLeadTo($this->dog);
        }

        /**
         * @var $currentLocation Location
         */
        while ($currentLocation = $this->routeManager->getNextLocation()) {
            $this->get(TransferManager::class)->transferEntityToLocation($this->human, $currentLocation);
            $this->get(TransferManager::class)->transferEntityToLocation($this->dog, $currentLocation);
            foreach ($currentLocation->getObjects() as $object) {
                $this->human->interactWithObject($object);
                $this->dog->interactWithObject($object);
            }
        }
    }

    public function run($configuration)
    {
        $this->initialize($configuration);
        $this->runScenario();
    }
}
