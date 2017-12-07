<?php
/**
 * Created by PhpStorm.
 * User: asandoval
 * Date: 05/12/2017
 * Time: 11:55
 */

namespace Lithium\Exceptions;


use Throwable;

class UnsupportedTypeException extends \Exception {

    private $title = "Missing parameter exception:";

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null) {
        parent::__construct("{$this->title} $message", $code, $previous);
    }

}