<?php
use Phalcon\Mvc\Micro\Collection as MicroCollection;

$IndexCollection = new MicroCollection();
$IndexCollection->setHandler(new IndexController());
$IndexCollection->setPrefix("/users");

$IndexCollection->get("/get", "get_products");

$IndexCollection->post("/", "resist_products");

$IndexCollection->get("/search/{name}", "search_products");

$IndexCollection->put("/{id:[0-9]+}", "update_products");

$IndexCollection->delete("/{id:[0-9]+}", "delete_products");

$app->mount($IndexCollection);