<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>入社前課題</title>
</head>
<body>
<?php
/*(ここから)phalcon tutorial */

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Mvc\Controller;


// リソースの特定に役立つ絶対パス定数を定義する
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// オートローダーの登録
$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
    ]
    );

$loader->register();

// DIの生成
$di = new FactoryDefault();

// ビューコンポーネントの設定
$di->set(
'view',
function () {
    $view = new View();
    $view->setViewsDir(APP_PATH . '/views/');
    return $view;
}
);

// ベースURIの設定
$di->set(
'url',
function () {
    $url = new UrlProvider();
    $url->setBaseUri('/');
    return $url;
}
);

$application = new Application($di);

try {
    // リクエストのハンドリング
    $response = $application->handle();

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}
/*(ここまで) phalcon tutorial*/

class dat{
    public $image;
    public $name;
    public $explanation;
    public $price;
}
?>

</body>
</html>
