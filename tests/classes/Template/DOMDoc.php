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
class classes_template_domdoc
{

    public static function suite()
    {
        $suite = new \r8\Test\Suite;
        $suite->addTestSuite( 'classes_template_DOMDoc_Standard' );
        $suite->addTestSuite( 'classes_template_DOMDoc_Output' );
        return $suite;
    }

}

/**
 * unit tests
 */
class classes_template_DOMDoc_Standard extends PHPUnit_Framework_TestCase
{

    public function testRender ()
    {
        $doc = new DOMDocument;
        $doc->appendChild( $doc->createElement("tag") );

        $tpl = new \r8\Template\DOMDoc( $doc );

        $this->assertSame(
                '<?xml version="1.0"?>' ."\n"
                .'<tag/>' ."\n",
                $tpl->render()
            );
    }

    public function testToString ()
    {
        $doc = new DOMDocument;
        $doc->appendChild( $doc->createElement("tag") );

        $tpl = new \r8\Template\DOMDoc( $doc );

        $this->assertSame(
                '<?xml version="1.0"?>' ."\n"
                .'<tag/>' ."\n",
                "$tpl"
            );
    }

}

class classes_template_DOMDoc_Output extends PHPUnit_Extensions_OutputTestCase
{

    public function testDisplay_string ()
    {
        $this->expectOutputString(
                '<?xml version="1.0"?>' ."\n"
                .'<tag/>' ."\n"
            );

        $doc = new DOMDocument;
        $doc->appendChild( $doc->createElement("tag") );

        $tpl = new \r8\Template\DOMDoc( $doc );

        $this->assertSame( $tpl, $tpl->display() );
    }

}

?>