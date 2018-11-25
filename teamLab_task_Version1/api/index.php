<?php

use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;
use Phalcon\Http\Response;



// オートローダにディレクトリを登録する
$loader = new Loader();
$loader->registerNamespaces(
    [
        'Store\Products' => __DIR__ . '/models/',
    ]
    );

$loader->register();



// DIコンテナを作る
$di = new FactoryDefault();

// データベースサービスのセットアップ
$di->set(
    'db',
    function () {
        return new PdoMysql(
            [
                "host"     => "localhost",
                "username" => "root",
                "password" => "daQwuJzMO6zBHnEI",
                "dbname"   => "product",
                "options" => array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
                )
            ]
            );
    }
    );

$app = new Micro($di);

include __DIR__ . '/controllers/ControllerBase.php';
include __DIR__ . '/controllers/IndexController.php';
include __DIR__ . '/collections/IndexCollection.php';

$app->notFound(array(new ControllerBase(), "notFoundAction"));

$app->handle();