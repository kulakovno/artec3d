<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 21.03.2017
 * Time: 18:17
 */
require_once "vendor/autoload.php";
require_once "autoload.php";
$config = require_once "config.php";
$loop = React\EventLoop\Factory::create();
$monitor = new Artec3d\Monitor\Client($loop);


/**
 * Какие хотите нотиФикаторы используйте
 */
$sms = new Artec3d\Notifier\Sms($config['sms']);
$mail = new Artec3d\Notifier\Mail($config['mail']);

$google = new \Artec3d\Monitor\Resource\Url($config['resource']);
$google->addNotifier($sms);
$google->addNotifier($mail);

$monitor->setResource($google);
$monitor->start();

$loop->run();
