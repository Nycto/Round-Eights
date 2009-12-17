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
 * @copyright Copyright 2009, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_Transform_Chain extends PHPUnit_Framework_TestCase
{

    public function testConstruct ()
    {
        $trans1 = $this->getMock('r8\iface\Transform');
        $trans2 = $this->getMock('r8\iface\Transform');

        $chain = new \r8\Transform\Chain( $trans1, "string", $trans2 );
        $this->assertSame( array( $trans1, $trans2 ), $chain->getTransforms() );
    }

    public function testAddTransform ()
    {
        $chain = new \r8\Transform\Chain;
        $this->assertSame( array(), $chain->getTransforms() );

        $trans1 = $this->getMock('r8\iface\Transform');
        $this->assertSame( $chain, $chain->addTransform( $trans1 ) );
        $this->assertSame( array( $trans1 ), $chain->getTransforms() );

        $trans2 = $this->getMock('r8\iface\Transform');
        $this->assertSame( $chain, $chain->addTransform( $trans2 ) );
        $this->assertSame( array( $trans1, $trans2 ), $chain->getTransforms() );
    }

    public function testTo ()
    {
        $chain = new \r8\Transform\Chain;

        $chain->addTransform( new \r8\Transform\Deflate );
        $chain->addTransform( new \r8\Transform\Base64 );

        $this->assertSame(
        	"S84ozctWyE9TSEksSQQA",
            $chain->to( "chunk of data" )
        );
    }

    public function testFrom ()
    {
        $chain = new \r8\Transform\Chain;

        $chain->addTransform( new \r8\Transform\Deflate );
        $chain->addTransform( new \r8\Transform\Base64 );

        $this->assertSame(
        	"chunk of data",
            $chain->from( "S84ozctWyE9TSEksSQQA" )
        );
    }

}

?>