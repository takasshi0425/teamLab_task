<?php
use Phalcon\Mvc\Controller;

class SearchController extends Controller
{
    public function indexAction()
    {

    }

    public function resultAction()
    {
        header("location:/teamlab_task/api/users/search/Tシャツ");
    }
}