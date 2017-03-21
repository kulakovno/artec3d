<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 21.03.2017
 * Time: 19:00
 */

namespace Artec3d\Monitor\Resource;


use Artec3d\Notifier\NotifierInterface;

interface ResourceInterface
{
    /**
     * Проверка валидности ресурса
     * @return bool
     */
    public function validate() : bool;

    /**
     * Будет использовано при получении статуса
     * @return string
     */
    public function getBody(): string;

    /**
     * Установка нотификатора
     * @param NotifierInterface $notifier
     */
    public function addNotifier(NotifierInterface $notifier);

    /**
     * Проверка сохраненной доступности ресурса
     * @return bool
     */
    public function isAvailable() : bool;

    /**
     * Сохранение доступности ресурса
     * @param bool $value
     */
    public function setAvailability(bool $value);

    /**
     * Оповещение всех нотификаторов
     * @param string $message
     */
    public function applyResourceNotifiers(string $message);
}