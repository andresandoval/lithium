<?php
/**
 * Created by PhpStorm.
 * User: asandoval
 * Date: 05/12/2017
 * Time: 11:10
 */

namespace Lithium\Exceptions;


use Throwable;

class ClassNotFoundException extends \Exception {

    private $title = "Class not found exception:";

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null) {
        parent::__construct("{$this->title} $message", $code, $previous);
    }
}