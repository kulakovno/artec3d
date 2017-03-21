<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 21.03.2017
 * Time: 18:53
 */

namespace Artec3d\Notifier;

/**
 * Класс нотификатора почты.
 * @package Artec3d\Notifier
 */
class Mail implements NotifierInterface
{
    protected $transport;
    protected $client;
    private $_config = [];
    private $_enabled = false;

    /**
     * Mail constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->_config = $config;
        $this->transport = \Swift_SmtpTransport::newInstance($this->_config['url'], $this->_config['port']);
        $this->transport->setUsername($this->_config['username']);
        $this->transport->setPassword($this->_config['password']);
        $this->client = \Swift_Mailer::newInstance($this->transport);


    }

    /**
     * {@inheritdoc}
     * Базовая реализация нотификатора почты.
     * Т.к. у меня нет почтового сервера, ниже просто пример реализации.
     * @param string $message
     */
    public function notify(string $message)
    {
        echo PHP_EOL . "MAIL::" . $message;
        if ($this->_enabled) {
            $message = new \Swift_Message($this->_config['subject']);
            $message->setFrom($this->_config['from']);
            $message->setBody($message, 'text/html');
            $message->setTo($this->_config['to']);
            $this->client->send($message);
        }
    }

}