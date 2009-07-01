<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of raindropPHP.
 *
 * raindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * raindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with raindropPHP. If not, see <http://www.raindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_db_result_write extends PHPUnit_Framework_TestCase
{

    public function testGetAffected ()
    {
        $write = new \h2o\DB\Result\Write(
                5,
                null,
                "UPDATE table SET field = 'new' LIMIT 5"
            );

        $this->assertSame( 5, $write->getAffected() );


        $write = new \h2o\DB\Result\Write(
                null,
                null,
                "UPDATE table SET field = 'new' LIMIT 5"
            );

        $this->assertSame( 0, $write->getAffected() );


        $write = new \h2o\DB\Result\Write(
                -5,
                null,
                "UPDATE table SET field = 'new' LIMIT 5"
            );

        $this->assertSame( 0, $write->getAffected() );
    }

    public function testGetInsertID ()
    {
        $write = new \h2o\DB\Result\Write(
                5,
                null,
                "UPDATE table SET field = 'new' LIMIT 5"
            );

        $this->assertNull( $write->getInsertID() );


        $write = new \h2o\DB\Result\Write(
                5,
                FALSE,
                "UPDATE table SET field = 'new' LIMIT 5"
            );

        $this->assertNull( $write->getInsertID() );


        $write = new \h2o\DB\Result\Write(
                1,
                50,
                "INSERT INTO table SET field = 'new'"
            );

        $this->assertSame( 50, $write->getInsertID() );


        $write = new \h2o\DB\Result\Write(
                1,
                -10,
                "INSERT INTO table SET field = 'new'"
            );

        $this->assertNull( $write->getInsertID() );
    }

}

?>