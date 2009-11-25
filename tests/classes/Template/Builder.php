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
 * Test Suite
 */
class classes_Template_Builder extends PHPUnit_Framework_TestCase
{

    public function testBlank ()
    {
        $builder = new \r8\Template\Builder;

        $this->assertThat(
            $builder->blank(),
            $this->isInstanceOf('\r8\Template\Blank')
        );
    }

    public function testCollection ()
    {
        $builder = new \r8\Template\Builder;

        $this->assertThat(
            $builder->collection(),
            $this->isInstanceOf('\r8\Template\Collection')
        );
    }

    public function testDOMDocument ()
    {
        $builder = new \r8\Template\Builder;

        $this->assertThat(
            $builder->domDoc( new DOMDocument ),
            $this->isInstanceOf('\r8\Template\DOMDoc')
        );
    }

    public function testRaw ()
    {
        $builder = new \r8\Template\Builder;

        $result = $builder->raw( "content" );

        $this->assertThat( $result, $this->isInstanceOf('\r8\Template\Raw') );
        $this->assertSame( "content", $result->getContent() );
    }

}

?>