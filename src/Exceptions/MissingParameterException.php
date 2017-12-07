<?php
/**
 * Created by PhpStorm.
 * User: asandoval
 * Date: 06/12/2017
 * Time: 15:08
 */

namespace Lithium\Exceptions;


use Throwable;

final class MissingParameterException extends \Exception {

    private $title = "Missing parameter exception:";

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null) {
        parent::__construct("{$this->title} $message", $code, $previous);
    }

}