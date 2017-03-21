<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 21.03.2017
 * Time: 18:45
 */

namespace Artec3d\Monitor;


use Artec3d\Monitor\Resource\ResourceInterface;

/**
 * Interface ClientInterface
 * @package Artec3d\Monitor
 */
interface ClientInterface
{
    /**
     * Запускает мониторинг.
     */
    public function start();

    /**
     * Устанавливает ресурс для мониторинга
     * @param ResourceInterface $resource
     */
    public function setResource(ResourceInterface $resource);

    /**
     * Сохраняет актуальный статус ресурса
     */
    public function saveResourceStatus();

    /**
     * Получает статус актуальный статус ресурса
     * @return int
     */
    public function getResourceStatus() : int;

    /**
     * Настраивает поведение при реконнекте
     */
    public function setReconnectBehavior();

    /**
     * Все нотификаторы ресурса оповещают заданным сообщением, если ресурс недоступен
     * @param string $message
     */
    public function applyResourceFailedNotifications(string $message);

    /**
     * Все нотификаторы ресурса оповещают заданным сообщением, если ресурс доступен
     * @param string $message
     */
    public function applyResourceSuccessNotifications(string $message);


}