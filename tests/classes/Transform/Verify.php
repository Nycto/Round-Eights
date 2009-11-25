<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of Round Eights.
 *
 * Round Eights is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * Round Eights is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with Round Eights. If not, see <http://www.RoundEights.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <James@RoundEights.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_Transform_Verify extends PHPUnit_Framework_TestCase
{

    public function testPbkdf2 ()
    {
        $this->assertSame(
            "xiI1BLiArMftCkXgUpeZvkOLfE4PSvjL5q+SoPDIuoU=",
            base64_encode( \r8\Transform\Verify::pbkdf2( "to encrypt", "seed", 32 ) )
        );

        $this->assertSame(
            "vmt4T7HYuHbFEoAriuCcOH1TjsUqxld1eBIZc5zBKHI=",
            base64_encode( \r8\Transform\Verify::pbkdf2( "Another value", "seed", 32 ) )
        );

        $this->assertSame(
            "7f8UaP+BNX4urKerNjWvAfHBqe65uZ11KURdQJqylsU=",
            base64_encode( \r8\Transform\Verify::pbkdf2( "to encrypt", "other seed", 32 ) )
        );

        $this->assertSame(
            "xiI1BLiArMftCg==",
            base64_encode( \r8\Transform\Verify::pbkdf2( "to encrypt", "seed", 10 ) )
        );

        $this->assertSame(
            "gcYyB+vBwlnfMbYo8qzMDNo7dSvLxYsHoICLK81uItM=",
            base64_encode( \r8\Transform\Verify::pbkdf2( "to encrypt", "seed", 32, 50 ) )
        );

        $this->assertSame(
            "xiI1BLiArMftCkXgUpeZvkOLfE4PSvjL5q+SoPDIuoX+U0uzdNnJka7yNRs9z2HdYQ4=",
            base64_encode( \r8\Transform\Verify::pbkdf2( "to encrypt", "seed", 50 ) )
        );

        try {
            \r8\Transform\Verify::pbkdf2( "to encrypt", "seed", 137438953441 );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Derived key length is too long", $err->getMessage() );
        }
    }
    
    /**
     * Returns a test seed that will return the given string
     *
     * @return \r8\Random\Seed
     */
    public function getTestSeed ( $string )
    {
        $seed = $this->getMock('\r8\Random\Seed', array(), array(), '', FALSE);
        $seed->expects( $this->any() )
            ->method( "getString" )
            ->will( $this->returnValue($string) );
            
        return $seed;
    }
    
    public function testTo ()
    {
        $wrapped = $this->getMock('\r8\iface\Transform');
        $wrapped->expects( $this->once() )
            ->method( "to" )
            ->with( $this->equalTo("unencoded") )
            ->will( $this->returnValue("encoded") );
        
        
        $seed = $this->getTestSeed("seed data");
        
        $verify = new \r8\Transform\Verify( $wrapped, $seed );
        
        $this->assertSame(
            "9OgoBpBeCUGP2HMyNf1uHPPYNXJ1bgxZkfNAyjBgX3JlbmNvZGVk",
            base64_encode( $verify->to("unencoded") )
        );
    }
    
    public function testFrom ()
    {
        $wrapped = $this->getMock('\r8\iface\Transform');
        $wrapped->expects( $this->once() )
            ->method( "from" )
            ->with( $this->equalTo("encoded") )
            ->will( $this->returnValue("unencoded") );
        
        $seed = $this->getTestSeed("seed data");
        
        $verify = new \r8\Transform\Verify( $wrapped, $seed );
        
        $this->assertSame(
            "unencoded",
            $verify->from( base64_decode(
                "9OgoBpBeCUGP2HMyNf1uHPPYNXJ1bgxZkfNAyjBgX3JlbmNvZGVk"
            ))
        );
    }
    
    public function testFrom_NoHash ()
    {
        $wrapped = $this->getMock('\r8\iface\Transform');
        $seed = $this->getTestSeed("seed data");
        
        $verify = new \r8\Transform\Verify( $wrapped, $seed );
        
        try {
            $verify->from( "Not long enough" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Data $err ) {
            $this->assertSame( "Unable to extract data verification hash", $err->getMessage() );
        }
    }
    
    public function testFrom_Invalid ()
    {
        $wrapped = $this->getMock('\r8\iface\Transform');
        $seed = $this->getTestSeed("seed data");
        
        $verify = new \r8\Transform\Verify( $wrapped, $seed );
        
        try {
            $verify->from( base64_decode(
                "8OgoBpBeCUGP2HMyNf1uHPPYNXJ1bgxZkfNAyjBgX3JlbmNvZGVk"
            ) );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Data $err ) {
            $this->assertSame( "Data integrity verification failed", $err->getMessage() );
        }
    }

}

?>