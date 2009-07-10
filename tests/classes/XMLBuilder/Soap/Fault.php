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

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_xmlbuilder_soap_fault extends PHPUnit_Framework_TestCase
{

    public function testConstructErrs ()
    {
        try {
            new \h2o\XMLBuilder\Soap\Fault( "", "An error was encountered" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }

        try {
            new \h2o\XMLBuilder\Soap\Fault( "Error", "" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }
    }

    public function testBuildNode ()
    {
        $builder = new \h2o\XMLBuilder\Soap\Fault(
                "Error",
                "An error was encountered"
            );

        $doc = new \DOMDocument;

        $builtNode = $builder->buildNode( $doc );
        $this->assertThat( $builtNode, $this->isInstanceOf("DOMElement") );
        $this->assertSame( "soap:Fault", $builtNode->tagName );

        $this->assertSame(
                '<soap:Fault xmlns:soap="http://www.w3.org/2003/05/soap-envelope">'
                    .'<soap:Code><soap:Value>Error</soap:Value></soap:Code>'
                    .'<soap:Reason><soap:Text>An error was encountered</soap:Text></soap:Reason>'
                .'</soap:Fault>',
                $doc->saveXML( $builtNode )
            );
    }

}

?>