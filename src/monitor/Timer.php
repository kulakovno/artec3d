<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 21.03.2017
 * Time: 22:00
 */

namespace Artec3d\Monitor;

use React\EventLoop\LoopInterface;

/**
 * Class Timer
 * @package Artec3d\Monitor
 */
class Timer
{
    /**
     * @var ClientInterface
     */
    protected $monitor;

    /**
     * @var LoopInterface
     */
    protected $loop;

    /**
     * @var array
     */
    protected $timers = [];

    /**
     * Timer constructor.
     * @param ClientInterface $monitor
     * @param LoopInterface $loop
     */
    public function __construct(ClientInterface $monitor, LoopInterface $loop)
    {
        $this->monitor = $monitor;
        $this->loop = $loop;
    }

    /**
     * Установим и запомним таймеры исходя из условий задачи
     */
    public function set()
    {
        $this->timers[] = $this->loop->addPeriodicTimer(1 * 60, function () {
            $this->monitor->saveResourceStatus();
        });

        $this->timers[] = $this->loop->addPeriodicTimer(1 * 1, function () {
            $this->monitor->setReconnectBehavior();
        });

        $this->timers[] = $this->loop->addTimer(60 * 3, function () {
            $this->monitor->applyResourceFailedNotifications('Not available for 3 minutes');
        });

        $this->timers[] = $this->loop->addTimer(60 * 10, function () {
            $this->monitor->applyResourceFailedNotifications('Not available for 10 minutes');
        });

        $this->timers[] = $this->loop->addTimer(60 * 50, function () {
            $this->monitor->applyResourceFailedNotifications('Not available for 50 minutes');
        });

        $this->timers[] = $this->loop->addTimer(60 * 100, function () {
            $this->monitor->applyResourceFailedNotifications('Not available for 100 minutes');
        });

        $this->timers[] = $this->loop->addTimer(60 * 500, function () {
            $this->monitor->applyResourceFailedNotifications('Not available for 500 minutes');
        });
    }

    /**
     * Сброс и установка всех таймеров заново
     */
    public function reset()
    {
        $this->unset();
        $this->set();
    }

    /**
     * Сброс всех таймеров
     */
    public function unset()
    {
        foreach ($this->timers as $timer) {
            $this->loop->cancelTimer($timer);
        }

    }
}