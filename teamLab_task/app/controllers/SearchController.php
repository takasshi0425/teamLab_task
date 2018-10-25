<?php
use Phalcon\Mvc\Controller;

class SearchController extends Controller
{
    public function indexAction()
    {

    }

    public function resultAction()
    {
        $app->get(
            '/search/{name}',
            function ($name) use ($app) {
                $phql = 'SELECT * FROM app\contotollers\users WHERE name LIKE :name: ORDER BY name';

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
                        'exp' => $user->exp,
                        'price' => $user->price,
                    ];
                }

                echo json_encode($data);
            }
            );
    }
}