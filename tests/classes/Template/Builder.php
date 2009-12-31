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
 * Test Suite
 */
class classes_Template_Builder extends PHPUnit_Framework_TestCase
{

    public function getMockFinder ()
    {
        return $this->getMock('\r8\Finder', array(), array(), '', FALSE);
    }

    public function testBlank ()
    {
        $builder = new \r8\Template\Builder( $this->getMockFinder() );

        $this->assertThat(
            $builder->blank(),
            $this->isInstanceOf('\r8\Template\Blank')
        );
    }

    public function testCollection ()
    {
        $builder = new \r8\Template\Builder( $this->getMockFinder() );

        $this->assertThat(
            $builder->collection(),
            $this->isInstanceOf('\r8\Template\Collection')
        );
    }

    public function testDOMDocument ()
    {
        $builder = new \r8\Template\Builder( $this->getMockFinder() );

        $this->assertThat(
            $builder->domDoc( new DOMDocument ),
            $this->isInstanceOf('\r8\Template\DOMDoc')
        );
    }

    public function testRaw ()
    {
        $builder = new \r8\Template\Builder( $this->getMockFinder() );

        $result = $builder->raw( "content" );

        $this->assertThat( $result, $this->isInstanceOf('\r8\Template\Raw') );
        $this->assertSame( "content", $result->getContent() );
    }

    public function testReplace ()
    {
        $builder = new \r8\Template\Builder( $this->getMockFinder() );
        $builder->set( "one", 1 );
        $builder->set( "two", 2 );

        $result = $builder->replace( "Template Content" );

        $this->assertThat( $result, $this->isInstanceOf('\r8\Template\Replace') );
        $this->assertSame( "Template Content", $result->getTemplate() );
        $this->assertSame( array( "one" => 1, "two" => 2), $result->getValues() );
    }

    public function testPHP ()
    {
        $finder = $this->getMockFinder();

        $builder = new \r8\Template\Builder( $finder );
        $builder->set( "one", 1 );
        $builder->set( "two", 2 );

        $result = $builder->php( 'dir/tpl.php' );

        $this->assertThat( $result, $this->isInstanceOf('\r8\Template\PHP') );
        $this->assertSame( 'dir/tpl.php', $result->getFile() );
        $this->assertSame( $finder, $result->getFinder() );
        $this->assertSame( array( "one" => 1, "two" => 2), $result->getValues() );
    }

}

?>