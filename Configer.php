<?php

/**
 * Class Configer
 * Created by GreenMine
 * My GitHub: https://github.com/GreenMine
 */

class Configer
{
    /**
     * Configer constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $defaultconfig = [
            'dbInfo' =>[
                'host' => '127.0.0.1',
                'username' => 'root',
                'password' => '',
                'dbname' => 'mcpe',
                'tablename' => 'VKBinding'
            ],
            'vkinfo' => [
                'token' => '',
                'version' => '5.100'
            ]
        ];
        $this->configname = $config;
        if(!file_exists($this->configname)) {
            file_put_contents($this->configname,  json_encode($defaultconfig,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
        $this->config = json_decode(file_get_contents($this->configname), true);
    }

    /**
     * @param null $arg
     * @return array
     */
    public function getDBInfo($arg = null) {
        return $this->config['dbInfo'][$arg];
    }

    /**
     * @param null $arg
     * @return array
     */
    public function getVKInfo($arg = null) {
        return $this->config['vkinfo'][$arg];
    }


    public function updateConfigData() {
        $this->config = json_decode(file_get_contents($this->configname), true);
        return true;
    }

}