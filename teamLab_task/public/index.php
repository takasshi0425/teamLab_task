<?php

use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;



// オートローダにディレクトリを登録する
$loader = new Loader();
$loader->registerNamespaces(
    [
        'Store\Products' => __DIR__ . '/models/',
    ]
    );
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

// ベースURIを設定して、生成される全てのURIが「teamLab_task」を含むようにする
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
                "options" => array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
                )
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

$app = new Micro($di);

// 全ての users を取得
$app->get(
    '/api/users',
    function () {
        // 全 usert を取得する操作
        $phql = 'SELECT * FROM Store\Products\Users ORDER BY name';

        $users = $app->modelsManager->executeQuery($phql);

        $data = [];

        foreach ($users as $user) {
            $data[] = [
                'id'   => $user->id,
                'name' => $user->name,

            ];
        }

        echo json_encode($data);
    }
    );


// 名前が $name である userを検索
$app->get(
    '/api/users/search/{name}',
    function ($name) {
        // 名前が $name である userを検索する操作
        $phql = 'SELECT * FROM StoreProducts\Users WHERE name LIKE :name: ORDER BY name';

        $users = $app->modelsManager->executeQuery(
            $phql,
            [
                'name' => '%' . $name . '%'
            ]
            );

        $data = [];

        foreach ($users as $user) {
            $data[] = [
                'id'   => $user->id,
                'name' => $user->name,
                'exp'  => $user->exp,
                'price'=> $user->price,
            ];
        }

        echo json_encode($data);
    }
    );

// プライマリーキーで userを指定して取得
$app->get(
    '/api/users/{id:[0-9]+}',
    function ($id) {
        // プライマリーキーが $idの userを指定して取得する操作
        $phql = 'SELECT * FROM Store\Producs\Users WHERE id = :id:';

        $user = $app->modelsManager->executeQuery(
            $phql,
            [
                'id' => $id,
            ]
            )->getFirst();

            // レスポンスを作成
            $response = new Response();

            if ($user === false) {
                $response->setJsonContent(
                    [
                        'status' => 'NOT-FOUND'
                    ]
                    );
            } else {
                $response->setJsonContent(
                    [
                        'status' => 'FOUND',
                        'data'   => [
                            'id'   => $user->id,
                            'name' => $user->name,
                            'exp'  => $user->exp,
                            'price'=> $user->price
                        ]
                    ]
                    );
            }

            return $response;
    }
    );

// 新しいrobotの追加
$app->post(
    '/api/users',
    function () {
        // 新しいuserを追加する操作
    }
    );

// プライマリーキーで指定したuserを更新する
$app->put(
    '/api/users/{id:[0-9]+}',
    function ($id) {
        // プライマリーキーが $id のuserを更新する
        $user = $app->request->getJsonRawBody();

        $phql = 'UPDATE Store\Products\Users SET name = :name:, exp = :exp:, price = :price: WHERE id = :id:';

        $status = $app->modelsManager->executeQuery(
            $phql,
            [
                'id'   => $id,
                'name' => $user->name,
                'exp'  => $user->exp,
                'price'=> $user->price,
            ]
            );

        // レスポンスの作成
        $response = new Response();

        // この挿入が成功したか確認する
        if ($status->success() === true) {
            $response->setJsonContent(
                [
                    'status' => 'OK'
                ]
                );
        } else {
            // HTTP ステータスの変更
            $response->setStatusCode(409, 'Conflict');

            $errors = [];

            foreach ($status->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }

            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => $errors,
                ]
                );
        }

        return $response;
    }
    );

// プライマリーキーで指定したuserを削除する
$app->delete(
    '/api/users/{id:[0-9]+}',
    function ($id) {
        // プライマリーキーが $id のuserを削除する
        $phql = 'DELETE FROM Store\Products\Users WHERE id = :id:';

        $status = $app->modelsManager->executeQuery(
            $phql,
            [
                'id' => $id,
            ]
            );

        // レスポンスの作成
        $response = new Response();

        if ($status->success() === true) {
            $response->setJsonContent(
                [
                    'status' => 'OK'
                ]
                );
        } else {
            // HTTPステータスの変更
            $response->setStatusCode(409, 'Conflict');

            $errors = [];

            foreach ($status->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }

            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => $errors,
                ]
                );
        }

        return $response;

    }
    );

$app->handle();