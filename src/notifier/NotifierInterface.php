<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 21.03.2017
 * Time: 18:52
 */

namespace Artec3d\Notifier;

interface NotifierInterface
{
    /**
     * Нотификация. Обязательно реализовать.
     * @param string $message
     */
    public function notify(string $message);
}