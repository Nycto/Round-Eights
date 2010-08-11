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
class classes_Log_Node_Stream extends PHPUnit_Framework_TestCase
{

    public function testDispatch ()
    {
        $stream = $this->getMock('\r8\iface\Stream\Out');
        $stream->expects( $this->once() )->method( "write" )
            ->with( $this->logicalAnd(
                $this->stringContains('Msg'),
                $this->stringContains('Error'),
                $this->stringContains('123')
            ) );

        $node = new \r8\Log\Node\Stream( $stream );

        $this->assertSame(
            $node,
            $node->dispatch( new \r8\Log\Message("Msg", "Error", 123) )
        );
    }

}

?>