<?php
/**
 * Created by PhpStorm.
 * User: asandoval
 * Date: 06/12/2017
 * Time: 11:08
 */

require_once "../vendor/autoload.php";

$doc = "
/**
 * Class SampleObjectWithMetadata
 * @package Lithium\Tests
 *
 * @SampleMetadata(
 *     @name foo1,
 *     @name foo2 sdsd      
 *     @name foo3 sdsd      asas
       @foo(@fooProperty=fooDalta, @fooProperty=fooDalta1)
 * )
 *
 */
";

$foo = \Lithium\Worker\DocMetadata::get($doc, \Lithium\Tests\SampleMetadata::class);

echo "<pre>";
print_r($foo);
echo "</pre>";
