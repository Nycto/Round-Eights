<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of RaindropPHP.
 *
 * RaindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * RaindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with RaindropPHP. If not, see <http://www.RaindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@Raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
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
        $suite = new h2o_Base_TestSuite;
        $suite->addTestSuite( 'classes_template_domdoc_standard' );
        $suite->addTestSuite( 'classes_template_domdoc_output' );
        return $suite;
    }

}

/**
 * unit tests
 */
class classes_template_domdoc_standard extends PHPUnit_Framework_TestCase
{

    public function testRender ()
    {
        $doc = new DOMDocument;
        $doc->appendChild( $doc->createElement("tag") );

        $tpl = new \h2o\Template\DOMDoc( $doc );

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

        $tpl = new \h2o\Template\DOMDoc( $doc );

        $this->assertSame(
                '<?xml version="1.0"?>' ."\n"
                .'<tag/>' ."\n",
                "$tpl"
            );
    }

}

class classes_template_domdoc_output extends PHPUnit_Extensions_OutputTestCase
{

    public function testDisplay_string ()
    {
        $this->expectOutputString(
                '<?xml version="1.0"?>' ."\n"
                .'<tag/>' ."\n"
            );

        $doc = new DOMDocument;
        $doc->appendChild( $doc->createElement("tag") );

        $tpl = new \h2o\Template\DOMDoc( $doc );

        $this->assertSame( $tpl, $tpl->display() );
    }

}

?>