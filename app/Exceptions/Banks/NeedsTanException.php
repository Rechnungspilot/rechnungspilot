<?php

namespace App\Exceptions\Banks;

class NeedsTanException extends \Exception
{
    private $action;

    public function __construct($message, $action)
    {
        $this->action = $action;
        parent::__construct($message);
    }

    public function action()
    {
        return $this->action;
    }
}