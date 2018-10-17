<?php
/**
 * Created by PhpStorm.
 * User: arnaud
 * Date: 17/10/18
 * Time: 15:44
 */

namespace App\Exceptions;


class AccountInactiveException extends \Exception
{

    /**
     * AccountInactiveException constructor.
     * @param string $string
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}