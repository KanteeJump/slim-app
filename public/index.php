<?php

declare(strict_types=1);

require __DIR__ . "/../vendor/autoload.php";

use Illuminate\Database\Capsule\Manager as Capsule;
use Slim\Factory\AppFactory;
use app\Application;

session_start();

$capsule = new Capsule();

$capsule->addConnection([
    "driver" => "sqlite",
    "database" => __DIR__ . "/../database/database.sqlite",
    "prefix" => "",
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

new Application(AppFactory::create());

Application::getInstance()->execute();
