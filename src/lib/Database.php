<?php

namespace App\lib;

use PDO;

class Database
{
    private
        $container, $settings;

    public function __construct($container)
    {
        $this->container = $container;
        $this->settings = $this->container->get('settings')['database'];

        $this->connection();
    }

    private function connection()
    {
        $dsn = $this->settings['driver'] . ':host=' . $this->settings['host'] . ';port=' . $this->settings['port'] . ';dbname=' . $this->settings['database'];
    
        return new PDO($dsn, $this->settings['username'], $this->settings['password']);
    }
}
