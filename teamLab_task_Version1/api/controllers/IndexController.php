<?php
use Phalcon\Http\Response;

class IndexController extends ControllerBase{

    // 全商品を取得する操作
    public function get_products(){
        $phql = 'SELECT * FROM Store\Products\Users ORDER BY id';

        $users = $this->modelsManager->executeQuery($phql);

        $data = [];

        foreach ($users as $user) {
            $data[] = [
                'id' => $user->id,
                'name' => $user->name,

            ];
        }

        echo json_encode($data);
    }

    //商品の登録
    public function resist_products(){
        $user = $this->request->getJsonRawBody();

        $phql = 'INSERT INTO Store\Products\Users (name, exp, price) VALUES (:name:, :exp:, :price:)';

        $status = $this->modelsManager->executeQuery(
            $phql,
            [
                'name'  => $user->name,
                'exp'   => $user->exp,
                'price' => $user->price,
            ]
            );

        // レスポンスの作成
        $response = new Response();

        //画像保存(画像はbase64形式)
        $image_file = __DIR__;
        $image_file = str_replace("controllers", "images", $image_file);
        $image_file = $image_file."/".($status->getModel()->id).".dat";
        $image_file = file_put_contents($image_file, $user->image);

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

    //商品の検索
    public function search_products($name){
        $phql = 'SELECT * FROM Store\Products\Users WHERE name LIKE :name: ORDER BY name';

        $users = $this->modelsManager->executeQuery(
            $phql,
            [
                'name' => '%' . $name . '%'
            ]
            );

        $data = [];

        foreach ($users as $user) {
            $image_file = __DIR__;
            $image_file = str_replace("controllers", "images", $image_file);
            $image = file_get_contents($image_file."/".($user->id).".dat");

            if($image == false){
                $image = "No image.";
            }

            $data[] = [
                'id'   => $user->id,
                'name' => $user->name,
                'exp'  => $user->exp,
                'price'=> $user->price,
                'image'=> $image,
            ];
        }
        echo json_encode($data);
    }

    //商品の更新
    public function update_products($id){
        $user = $this->request->getJsonRawBody();

        $phql = 'UPDATE Store\Products\Users SET name = :name:, exp = :exp:, price = :price: WHERE id = :id:';

        $status = $this->modelsManager->executeQuery(
            $phql,
            [
                'id'   => $id,
                'name' => $user->name,
                'exp'  => $user->exp,
                'price'=> $user->price,
            ]
            );

        //画像更新(画像はbase64形式)
        $image_file = __DIR__;
        $image_file = str_replace("controllers", "images", $image_file);
        $image_file = $image_file."/".($id).".dat";
        $image_file = file_put_contents($image_file, $user->image);

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

    //商品の削除
    public function delete_products($id){
        $phql = 'DELETE FROM Store\Products\Users WHERE id = :id:';

        $status = $this->modelsManager->executeQuery(
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
}