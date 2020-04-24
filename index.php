<?php

define('SCRIPT_ROOT', __DIR__);
define('DS', DIRECTORY_SEPARATOR);
define('BASE_CLASS_PATH', SCRIPT_ROOT . DS . 'classes');

require_once BASE_CLASS_PATH . DS . 'Application.php';
require_once BASE_CLASS_PATH . DS . 'Logger.php';
require_once BASE_CLASS_PATH . DS . 'Biome.php';
require_once BASE_CLASS_PATH . DS . 'Location.php';
require_once BASE_CLASS_PATH . DS . 'Lead.php';
require_once BASE_CLASS_PATH . DS . 'NeihgbourDoor.php';
require_once BASE_CLASS_PATH . DS . 'Tree.php';
require_once BASE_CLASS_PATH . DS . 'Human.php';
require_once BASE_CLASS_PATH . DS . 'Dog.php';

$itemLead = new Lead(Lead::OBJECT_CODE);
$itemNeighbourDoor1 = new NeihgbourDoor(NeihgbourDoor::OBJECT_CODE);
$itemNeighbourDoor2 = new NeihgbourDoor(NeihgbourDoor::OBJECT_CODE);
$itemTreeNearby1 = new Tree(Tree::OBJECT_CODE);
$itemTreeNearby2 = new Tree(Tree::OBJECT_CODE);
$itemTreeNextStreet1 = new Tree(Tree::OBJECT_CODE);
$itemTreeNextStreet2 = new Tree(Tree::OBJECT_CODE);

$biomeApartments = new Biome('apartments');

$locationApartmentsLivingRoom = new Location('living_room');

$locationApartmentsHall = new Location('hall');
$locationApartmentsHall->addObject($itemLead);

$biomeApartments->addLocation($locationApartmentsLivingRoom);
$biomeApartments->addLocation($locationApartmentsHall);


$biomeOutside = new Biome('outside');

$locationApartmentsOutside = new Location('porch');
$locationApartmentsOutside->addObject($itemNeighbourDoor1);
$locationApartmentsOutside->addObject($itemNeighbourDoor2);
$biomeOutside->addLocation($locationApartmentsOutside);

$locationStreetNearby = new Location('street_nearby');
$locationStreetNearby->addObject($itemTreeNearby1);
$locationStreetNearby->addObject($itemTreeNearby2);
$biomeOutside->addLocation($locationStreetNearby);

$locationStreetNext = new Location('street_next');
$locationStreetNext->addObject($itemTreeNextStreet1);
$locationStreetNext->addObject($itemTreeNextStreet2);
$biomeOutside->addLocation($locationStreetNearby);

$human = new Human($locationApartmentsLivingRoom, 'John');
$human->addHabit(NeihgbourDoor::OBJECT_CODE, function() use ($human) {
    $habits = [
        function() use ($human) { Application::getInstance()->getLogger()->info("{$human->getName()} thinks: \"This bitch still owe me money...\""); },
        function() use ($human) { Application::getInstance()->getLogger()->info("{$human->getName()} thinks: \"It's too late for that noise.. Weird\""); },
        function() use ($human) { Application::getInstance()->getLogger()->info("{$human->getName()} thinks: \"Nobody's home\""); },
        function() use ($human) { Application::getInstance()->getLogger()->info("{$human->getName()} passing by the door"); },
    ];
    $habits[array_rand($habits)]();
});
$human->addHabit(Tree::OBJECT_CODE, function() use ($human) {
    $habits = [
        function() use ($human) { Application::getInstance()->getLogger()->info("{$human->getName()} thinks: \"Such a beautiful tree\""); },
        function() use ($human) { Application::getInstance()->getLogger()->info("{$human->getName()} thinks: \"Green leafs! I like spring!\""); },
        function() use ($human) { Application::getInstance()->getLogger()->info("{$human->getName()} thinks: \"Fucking birds are too noisy\""); },
        function() use ($human) { Application::getInstance()->getLogger()->info("{$human->getName()} walks nearby"); },
    ];
    $habits[array_rand($habits)]();
});

$dog = new Dog($locationApartmentsLivingRoom);
$dog->addNickname('Roxy');
$dog->addNickname('Dog');
$dog->addNickname('Doggy');
$dog->addHabit(NeihgbourDoor::OBJECT_CODE, function() use ($dog) {
    $habits = [
        function() use ($dog) { Application::getInstance()->getLogger()->info("{$dog->getName()} sniffing around"); },
        function() use ($dog) { Application::getInstance()->getLogger()->info("{$dog->getName()} growls and barks on the door"); },
        function() use ($dog) { Application::getInstance()->getLogger()->info("{$dog->getName()} follows {$dog->getLead()->getLeader()->getName()}"); },
    ];
    $habits[array_rand($habits)]();
});
$dog->addHabit(Tree::OBJECT_CODE, function() use ($dog) {
    $habits = [
        function() use ($dog) { Application::getInstance()->getLogger()->info("{$dog->getName()} marks a new tree"); },
        function() use ($dog) { Application::getInstance()->getLogger()->info("{$dog->getName()} tries to catch a squirrel"); },
        function() use ($dog) { Application::getInstance()->getLogger()->info("{$dog->getName()} lays around"); },
        function() use ($dog) { Application::getInstance()->getLogger()->info("{$dog->getName()} looking for a ball"); },
    ];
    $habits[array_rand($habits)]();
});


$configuration = [
    'biomes' => [
        $biomeApartments,
        $biomeOutside
    ],
    'route' => [
        $locationApartmentsOutside,
        $locationStreetNearby,
        $locationStreetNext,
        $locationStreetNearby,
        $locationApartmentsOutside,
        $locationApartmentsHall
    ],
    'human' => $human,
    'dog' => $dog,
];

Application::getInstance()->run($configuration);
