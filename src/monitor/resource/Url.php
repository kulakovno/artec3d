<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 21.03.2017
 * Time: 19:01
 */

namespace Artec3d\Monitor\Resource;


use Artec3d\Notifier\NotifierInterface;

/**
 * Class Url
 * @package Artec3d\Monitor\Resource
 */
class Url implements ResourceInterface
{
    const STATUS_OK = 200;
    protected $url;
    protected $available = true;

    protected $notifiers = [];

    /**
     * Url constructor.
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * {@inheritdoc}
     * Реализация валидации, как пример.
     * @return bool
     */
    public function validate() : bool
    {
        return filter_var($this->url, FILTER_VALIDATE_URL);
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function getBody(): string
    {
        return $this->url;
    }

    /**
     * {@inheritdoc}
     * @return bool
     */
    public function isAvailable() : bool
    {
        return $this->available;
    }

    /**
     * {@inheritdoc}
     * @param bool $value
     */
    public function setAvailability(bool $value)
    {
        $this->available = $value;
    }

    /**
     * {@inheritdoc}
     * @param NotifierInterface $notifier
     */
    public function addNotifier(NotifierInterface $notifier)
    {
        $this->notifiers[] = $notifier;
    }

    /**
     * {@inheritdoc}
     * Пробросит в нотификатор сообщение "{дата}::ресурс::сообщение".
     * @param string $message
     */
    public function applyResourceNotifiers(string $message)
    {
        /**
         * @var NotifierInterface $notifier
         */
        foreach ($this->notifiers as $notifier) {
            $notifier->notify(date('Y-m-d H:i:s') . '::' . $this->url . '::' . $message);
        }
    }


}