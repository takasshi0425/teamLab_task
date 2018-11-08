<?php

namespace Store\Products;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Message;
use Phalcon\Mvc\Model\Validator\Uniqueness;
use Phalcon\Mvc\Model\Validator\InclusionIn;

class Users extends Model
{
    public $id;

    public $name;

    public $exp;

    public $price;

}