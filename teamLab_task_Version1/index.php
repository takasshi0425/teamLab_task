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

// 全ての users を取得
$app->get(
    '/api/users',
    function () use ($app){
        // 全 usert を取得する操作
        $phql = 'SELECT * FROM Store\Products\Users ORDER BY name';

        $users = $app->modelsManager->executeQuery($phql);

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


// 名前が $name である userを検索
$app->get(
    '/api/users/search/{name}',
    function ($name)use ($app) {
        // 名前が $name である userを検索する操作
        $phql = 'SELECT * FROM Store\Products\Users WHERE name LIKE :name: ORDER BY name';

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
    function ($id)use ($app) {
        // プライマリーキーが $idの userを指定して取得する操作
        $phql = 'SELECT * FROM Store\Products\Users WHERE id = :id:';

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

// 新しい商品の追加
$app->post(
    '/api/users',
    function () use ($app) {
        $user = $app->request->getJsonRawBody();

        $phql = 'INSERT INTO Store\Products\Users (name, exp, price) VALUES (:name:, :exp:, :price:)';
        var_dump($user);
        $status = $app->modelsManager->executeQuery(
            $phql,
            [
                'name'  => $user->name,
                'exp'   => $user->exp,
                'price' => $user->price,
            ]
            );

        // レスポンスの作成
        $response = new Response();

        // 挿入が成功したかを確認
        if ($status->success() === true) {
            // HTTPステータスの変更
            $response->setStatusCode(201, 'Created');

            $user->id = $status->getModel()->id;

            $response->setJsonContent(
                [
                    'status' => 'OK',
                    'data'   => $user,
                ]
                );
        } else {
            // HTTPステータスの変更
            $response->setStatusCode(409, 'Conflict');

            // クライアントにエラーを送信
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

// プライマリーキーで指定したuserを更新する
$app->put(
    '/api/users/{id:[0-9]+}',
    function ($id)use ($app) {
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
    function ($id)use ($app) {
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