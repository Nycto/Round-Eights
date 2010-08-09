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

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_XMLBuilder_Soap_Fault extends PHPUnit_Framework_TestCase
{

    public function testBuildNode_basic ()
    {
        $fault = new \r8\Soap\Fault("Error");

        $builder = new \r8\XMLBuilder\Soap\Fault( $fault, "test:uri" );

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
        $fault = new \r8\Soap\Fault("Error");
        $fault->setRole( "role:uri" );

        $builder = new \r8\XMLBuilder\Soap\Fault( $fault, "test:uri" );

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
        $fault = new \r8\Soap\Fault("Oops", "mustunderstand", array("one", "two") );

        $builder = new \r8\XMLBuilder\Soap\Fault( $fault, "test:uri" );

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
        $fault = new \r8\Soap\Fault("Oops");
        $fault->setDetails(array("one" => "once", "two" => "twice"));

        $builder = new \r8\XMLBuilder\Soap\Fault( $fault, "test:uri" );

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
