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
 * @author James Frasca <James@RaindropPHP.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_xmlbuilder_soap_fault extends PHPUnit_Framework_TestCase
{

    public function testBuildNode_basic ()
    {
        $fault = new \h2o\Soap\Fault("Error");

        $builder = new \h2o\XMLBuilder\Soap\Fault( $fault, "test:uri" );

        $doc = new DOMDocument;
        $doc->appendChild( $builder->buildNode( $doc ) );

        $this->assertSame(
        	'<?xml version="1.0"?>' ."\n"
            .'<Fault xmlns="test:uri"><Code><Value>Sender</Value></Code><Reason><Text>Error</Text></Reason></Fault>' ."\n",
            $doc->saveXML()
        );
    }

    public function testBuildNode_Role ()
    {
        $fault = new \h2o\Soap\Fault("Error");
        $fault->setRole( "role:uri" );

        $builder = new \h2o\XMLBuilder\Soap\Fault( $fault, "test:uri" );

        $doc = new DOMDocument;
        $doc->appendChild( $builder->buildNode( $doc ) );

        $this->assertSame(
        	'<?xml version="1.0"?>' ."\n"
            .'<Fault xmlns="test:uri"><Code><Value>Sender</Value></Code><Reason><Text>Error</Text></Reason><Role>role:uri</Role></Fault>' ."\n",
            $doc->saveXML()
        );
    }

    public function testBuildNode_Subcodes ()
    {
        $fault = new \h2o\Soap\Fault("Oops", "mustunderstand", array("one", "two") );

        $builder = new \h2o\XMLBuilder\Soap\Fault( $fault, "test:uri" );

        $doc = new DOMDocument;
        $doc->appendChild( $builder->buildNode( $doc ) );

        $this->assertSame(
        	'<?xml version="1.0"?>' ."\n"
            .'<Fault xmlns="test:uri"><Code><Value>MustUnderstand</Value><Subcode><Value>one</Value><Subcode><Value>two</Value></Subcode></Subcode></Code><Reason><Text>Oops</Text></Reason></Fault>' ."\n",
            $doc->saveXML()
        );
    }

    public function testBuildNode_Details ()
    {
        $fault = new \h2o\Soap\Fault("Oops");
        $fault->setDetails(array("one" => "once", "two" => "twice"));

        $builder = new \h2o\XMLBuilder\Soap\Fault( $fault, "test:uri" );

        $doc = new DOMDocument;
        $doc->appendChild( $builder->buildNode( $doc ) );

        $this->assertSame(
        	'<?xml version="1.0"?>' ."\n"
            .'<Fault xmlns="test:uri"><Code><Value>Sender</Value></Code><Reason><Text>Oops</Text></Reason><Details><one>once</one><two>twice</two></Details></Fault>' ."\n",
            $doc->saveXML()
        );
    }

}

?>