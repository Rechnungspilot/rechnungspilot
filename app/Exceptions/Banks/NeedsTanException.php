<?php

namespace App\Exceptions\Banks;

class NeedsTanException extends \Exception
{
    private $action;
    private $path;

    public function __construct($message, $action, $path)
    {
        $this->action = $action;
        $this->path = $path;
        parent::__construct($message);
    }

    public function action()
    {
        return $this->action;
    }

    public function path()
    {
        return $this->path;
    }

}