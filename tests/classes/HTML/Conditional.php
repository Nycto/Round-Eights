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
class classes_HTML_Conditional extends PHPUnit_Framework_TestCase
{

    public function testConstruct ()
    {
        $condition = new \r8\HTML\Conditional('lt IE6');
        $this->assertSame( 'lt IE6', $condition->getCondition() );
        $this->assertNull( $condition->getContent() );

        $condition = new \r8\HTML\Conditional('lt IE6', 'content');
        $this->assertSame( 'lt IE6', $condition->getCondition() );
        $this->assertSame( 'content', $condition->getContent() );
    }

    public function testSetCondition ()
    {
        $condition = new \r8\HTML\Conditional('lt IE6');
        $this->assertSame( 'lt IE6', $condition->getCondition() );

        $this->assertSame( $condition, $condition->setCondition('lte IE7') );
        $this->assertSame( 'lte IE7', $condition->getCondition() );

        try {
            $condition->setCondition( "  " );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }
    }

    public function testGetOpenTag ()
    {
        $condition = new \r8\HTML\Conditional('lt IE6');
        $this->assertSame( '<!--[if lt IE6]>', $condition->getOpenTag() );

        $condition->setCondition('lte IE7');
        $this->assertSame( '<!--[if lte IE7]>', $condition->getOpenTag() );
    }

    public function testGetCloseTag ()
    {
        $condition = new \r8\HTML\Conditional('lt IE6');
        $this->assertSame( '<![endif]-->', $condition->getCloseTag() );
    }

    public function testRender ()
    {
        $condition = new \r8\HTML\Conditional('lt IE6');
        $this->assertSame( '<!--[if lt IE6]><![endif]-->', $condition->render() );

        $condition->setContent("chunk o data");
        $this->assertSame(
            '<!--[if lt IE6]>chunk o data<![endif]-->',
            $condition->render()
        );

        $condition->setCondition('lte IE7');
        $this->assertSame(
            '<!--[if lte IE7]>chunk o data<![endif]-->',
            $condition->render()
        );
    }

}

