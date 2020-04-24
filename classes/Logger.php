<?php

class Logger
{
    const LOG_LEVEL_INFO = 'INFO';

    public function __construct()
    {
        /**
         * Here can be initialization to files/databases/api/etc
         */
    }

    public function addRecord($level, $message)
    {
        echo (new DateTime())->format(DateTime::ISO8601) . ' ' . $level . ': ' . $message . PHP_EOL;
    }

    public function addInfo($message)
    {
        $this->addRecord(self::LOG_LEVEL_INFO, $message);
    }

    public function info($message)
    {
        $this->addInfo($message);
    }
}
