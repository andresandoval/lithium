<?php
/**
 * Created by PhpStorm.
 * User: asandoval
 * Date: 06/12/2017
 * Time: 11:03
 */

namespace Lithium\Tests;


class SampleMetadata {

    private $name;
    private $foo;

    public function __construct($n = null) {
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName(string ...$name): void {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getFoo() {
        return $this->foo;
    }

    /**
     * @param mixed $foo
     */
    public function setFoo(FooMetadata $foo): void {
        $this->foo = $foo;
    }



}