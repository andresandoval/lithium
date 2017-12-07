<?php
/**
 * Created by PhpStorm.
 * User: asandoval
 * Date: 05/12/2017
 * Time: 11:24
 */

namespace Lithium\Exceptions;


use Throwable;

class NullPointerException extends \Exception {

    private $title = "Null pointer exception:";

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null) {
        parent::__construct("{$this->title} $message", $code, $previous);
    }

}