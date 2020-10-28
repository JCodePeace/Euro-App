<?php


namespace Euro\ApplicationContext\Entity;


use Throwable;

class InvalidContextException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        parent::__toString(); // TODO: Change the autogenerated stub
    }

}