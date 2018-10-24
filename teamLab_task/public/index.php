<?php

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;



// オートローダにディレクトリを登録する
$loader = new Loader();

$loader->registerDirs(
    [
        "../app/controllers/",
        "../app/models/",
    ]
    );

$loader->register();



// DIコンテナを作る
$di = new FactoryDefault();

// ビューのコンポーネントの組み立て
$di->set(
    "view",
    function () {
        $view = new View();

        $view->setViewsDir("../app/views/");

        return $view;
    }
    );

// ベースURIを設定して、生成される全てのURIが「t」を含むようにする
$di->set(
    "url",
    function () {
        $url = new UrlProvider();

        $url->setBaseUri("/teamLab_task/");

        return $url;
    }
    );

// データベースサービスのセットアップ
$di->set(
    "db",
    function () {
        return new DbAdapter(
            [
                "host"     => "localhost",
                "username" => "root",
                "password" => "daQwuJzMO6zBHnEI",
                "dbname"   => "product",
            ]
            );
    }
    );

$application = new Application($di);

try {
    // リクエストを処理する
    $response = $application->handle();

    $response->send();
} catch (\Exception $e) {
    echo "Exception: ", $e->getMessage();
}