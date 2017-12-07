<?php
/**
 * Created by PhpStorm.
 * User: asandoval
 * Date: 06/12/2017
 * Time: 10:17
 */

namespace Lithium\Worker;


interface Worker {

    static function get(string $doc, string $metaClassName);

}