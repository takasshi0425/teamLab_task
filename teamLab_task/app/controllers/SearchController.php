<?php
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Micro;

$app = new Micro();

class SearchController extends Controller
{
    public function indexAction()
    {

    }

    public function resultAction()
    {
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
    }
    $app->handle();