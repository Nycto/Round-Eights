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
 * Unit Tests
 */
class classes_CLI_Arg extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a test argument
     *
     * @return \r8\CLI\Arg
     */
    public function getTestArg ( $name, $filter, $validator )
    {
        return $this->getMock(
            '\r8\CLI\Arg',
            array( 'consume', 'describe' ),
            array( $name, $filter, $validator )
        );
    }

    public function testConstructor ()
    {
        $filter = $this->getMock('\r8\iface\Filter');
        $validator = $this->getMock('\r8\iface\Validator');

        $arg = $this->getTestArg( "Name\0 Of \x10Arg", $filter, $validator );

        $this->assertSame( "Name Of Arg", $arg->getName() );
        $this->assertSame( $filter, $arg->getFilter() );
        $this->assertSame( $validator, $arg->getValidator() );
    }

    public function testConstructor_Defaults ()
    {
        $arg = $this->getTestArg("Arg", NULL, NULL);

        $this->assertThat(
            $arg->getFilter(),
            $this->isInstanceOf('\r8\Filter\Identity')
        );

        $this->assertThat(
            $arg->getValidator(),
            $this->isInstanceOf('\r8\Validator\Pass')
        );
    }

}

?>