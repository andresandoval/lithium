<?php
/**
 * Created by PhpStorm.
 * User: asandoval
 * Date: 07/12/2017
 * Time: 9:21
 */

namespace Lithium\Tests;


class FooMetadata {

    private $fooProperty;

    /**
     * @return mixed
     */
    public function getFooProperty() {
        return $this->fooProperty;
    }

    /**
     * @param mixed $fooProperty
     */
    public function setFooProperty(string ...$fooProperty): void {
        $this->fooProperty = $fooProperty;
    }



}