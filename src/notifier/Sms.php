<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 21.03.2017
 * Time: 18:53
 */

namespace Artec3d\Notifier;

use Twilio\Rest\Client;

/**
 * Класс нотификатора СМС.
 * @package Artec3d\Notifier
 */
class Sms implements NotifierInterface
{
    protected $client;
    private $_enabled = false;
    private $_config = [];

    public function __construct($config)
    {
        $this->_config = $config;
        $this->client = new Client($this->_config['sid'], $this->_config['token']);
        $this->_enabled = $this->_config['enabled'];
    }

    /**
     * {@inheritdoc}
     * Базовая реализация нотификатора СМС.
     * Т.к. у меня нет смс-шлюза это лишь пример реализации
     * @param string $message
     */
    public function notify(string $message)
    {
        echo PHP_EOL . "SMS::" . $message;
        if ($this->_enabled) {
            $this->client->messages->create(
                $this->_config['to'],
                array(
                    'from' => $this->_config['from'],
                    'body' => $message
                )
            );

        }
    }

}