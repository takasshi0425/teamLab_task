<?php

namespace Store\Products;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Message;
use Phalcon\Mvc\Model\Validator\Uniqueness;
use Phalcon\Mvc\Model\Validator\InclusionIn

class Users extends Model
{
    public $id;

    public $name;

    public $exp;

    public $price;

    public function validation()
    {
        // productの名前はユニークでなけばならない
        $this->validate(
            new Uniqueness(
                [
                    'field'   => 'name',
                    'message' => 'The product name must be unique',
                ]
                )
            );

        // yearは0以下にはできない
        if ($this->price < 0) {
            $this->appendMessage(
                new Message('The price cannot be less than zero')
                );
        }

        // メッセージが生成されているかを確認
        if ($this->validationHasFailed() === true) {
            return false;
        }
    }

}