<?php

//-->
/*
 * This file is part of the Image package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

class Eden_Tests_Image_ImageTest extends \PHPUnit_Framework_TestCase {

    public function testBlur() {
        $class = eden('image', realpath(__DIR__ . '/../../../assets/stars.gif'), 'gif');
//        var_dump($class->getDimensions());
        $this->assertInstanceOf('Eden\Image\Base', $class);
    }

}