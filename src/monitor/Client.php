<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 21.03.2017
 * Time: 18:43
 */

namespace Artec3d\Monitor;


use Artec3d\Exception\WrongResourceException;
use Artec3d\Monitor\Resource\ResourceInterface;
use Artec3d\Monitor\Resource\Url;

use Evenement\EventEmitter;
use GuzzleHttp\RequestOptions;
use React\EventLoop\LoopInterface;

/**
 * Class Client
 * @package Artec3d\Monitor
 */
class Client implements ClientInterface
{
    const METHOD = 'GET';
    protected $loop;
    protected $gate;
    /**
     * @var ResourceInterface|Url $resource
     */
    protected $resource;
    protected $emitter;
    protected $timers;

    /**
     * Client constructor.
     * @param LoopInterface $loop
     */
    public function __construct(LoopInterface $loop)
    {
        $this->loop = $loop;

        $this->emitter = new EventEmitter();;
        $this->timers = new Timer($this, $this->loop);
        $this->gate = new \GuzzleHttp\Client([
            RequestOptions::VERIFY => false
        ]);
    }

    /**
     * {@inheritdoc}
     * Установка таймеров.
     */
    public function start()
    {
        $this->timers->set();
    }

    /**
     * {@inheritdoc}
     * @param ResourceInterface $resource
     * @throws WrongResourceException
     */
    public function setResource(ResourceInterface $resource)
    {
        if (!$resource->validate()) {
            throw new WrongResourceException('Error creating resource');
        }
        $this->resource = $resource;
    }

    /**
     * {@inheritdoc}
     */
    public function saveResourceStatus()
    {
        $result = $this->getResourceStatus();
        if ($result === Url::STATUS_OK) {
            $this->resource->setAvailability(true);
        } else {
            $this->resource->setAvailability(false);
        }
    }

    /**
     * {@inheritdoc}
     * @return int
     */
    public function getResourceStatus() : int
    {
        $result = $this->gate->request(self::METHOD, $this->resource->getBody());
        return $result->getStatusCode();
    }

    /**
     * {@inheritdoc}
     * Установка разовых колбеков при реконнекте
     */
    public function setReconnectBehavior()
    {
        if ($this->resource->isAvailable()) {
            $this->emitter->emit(get_class($this->resource), ['Reconnected']);
        } else {
            $this->emitter->removeAllListeners(get_class($this->resource));
            $this->emitter->once(get_class($this->resource), [$this->timers, 'reset']);
            $this->emitter->once(get_class($this->resource), [$this->resource, 'applyResourceNotifiers']);
        }
    }

    /**
     * {@inheritdoc}
     * @param string $message
     */
    public function applyResourceFailedNotifications(string $message)
    {
        if (!$this->resource->isAvailable()) {
            $this->resource->applyResourceNotifiers($message);
        }
    }

    /**
     * {@inheritdoc}
     * @param string $message
     */
    public function applyResourceSuccessNotifications(string $message)
    {
        if ($this->resource->isAvailable()) {
            $this->resource->applyResourceNotifiers($message);
        }

    }

}