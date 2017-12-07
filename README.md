# LITHIUM

Library for managing metadata in Objects and/or Methods definitions

####Example

    <?php
     /**
     * @MetaSample(
     *   @propertyOne hello
     *   @propertyTwo world
     *   @propertyThree()
     *   @propertyFour(@subP41=001, @subp42=002)
     *   @propertyFive(
     *      @subP51=001
     *      @subp62=002
     *   ),
     *   @propertySix six, @propertySeven=seven 
     * )
     */
     class Test{...}