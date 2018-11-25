<?php

namespace Store\Products;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Message;
use Phalcon\Mvc\Model\Validator\Uniqueness;
use Phalcon\Mvc\Model\Validator\InclusionIn;

class Users extends Model
{
    public function validation()
    {

        //価格が0未満でエラー
        if ($this->price < 0) {
            $this->appendMessage(
                new Message("The price cannot be less than zero")
                );
        }

        if ($this->validationHasFailed() === true) {
            return false;
        }
    }

}