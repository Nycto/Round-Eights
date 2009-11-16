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
 * unit tests
 */
class classes_curry_unbound extends PHPUnit_Framework_TestCase
{

    public function testCall ()
    {
        $curry = $this->getMock("r8\Curry\Unbound", array("rawExec"));

        $curry->expects($this->once())
            ->method('rawExec')
            ->with( $this->equalTo( array("wakka", "test1") ) );

        $curry->exec("wakka", "test1");
    }

    public function testCallWithLeftRight ()
    {
        $curry = $this->getMock("r8\Curry\Unbound", array("rawExec"));
        $curry->setLeft("l1", "l2");
        $curry->setRight("r1", "r2");

        $curry->expects($this->once())
            ->method('rawExec')
            ->with( $this->equalTo( array("l1", "l2", 1, 2, 3, "r1", "r2") ) );

        $curry->exec(1, 2, 3);
    }

    public function testCallWithSlicing ()
    {
        $curry = $this->getMock("r8\Curry\Unbound", array("rawExec"));
        $curry->setLeft("l1", "l2");
        $curry->setRight("r1", "r2");
        $curry->setOffset(2)->setLimit(2);

        $curry->expects($this->once())
            ->method('rawExec')
            ->with( $this->equalTo( array("l1", "l2", 3, 4, "r1", "r2") ) );

        $curry->exec(1, 2, 3, 4, 5, 6);
    }

    public function testApply ()
    {
        $curry = $this->getMock("r8\Curry\Unbound", array("rawExec"));

        $curry->expects($this->once())
            ->method('rawExec')
            ->with( $this->equalTo( array("wakka", "test1") ) );

        $curry->apply( array("wakka", "test1") );
    }

    public function testApplyWithLeftRight ()
    {
        $curry = $this->getMock("r8\Curry\Unbound", array("rawExec"));
        $curry->setLeft("l1", "l2");
        $curry->setRight("r1", "r2");

        $curry->expects($this->once())
            ->method('rawExec')
            ->with( $this->equalTo( array("l1", "l2", 1, 2, 3, "r1", "r2") ) );

        $curry->apply( array(1, 2, 3) );
    }

    public function testApplyWithSlicing ()
    {
        $curry = $this->getMock("r8\Curry\Unbound", array("rawExec"));
        $curry->setLeft("l1", "l2");
        $curry->setRight("r1", "r2");
        $curry->setOffset(2)->setLimit(2);

        $curry->expects($this->once())
            ->method('rawExec')
            ->with( $this->equalTo( array("l1", "l2", 3, 4, "r1", "r2") ) );

        $curry->apply( array(1, 2, 3, 4, 5, 6) );
    }

    public function testInvoke ()
    {
        $curry = $this->getMock("r8\Curry\Unbound", array("rawExec"));

        $curry->expects($this->once())
            ->method('rawExec')
            ->with( $this->equalTo( array("wakka", "test1") ) );

        $curry("wakka", "test1");
    }

    public function testInvokeWithLeftRight ()
    {
        $curry = $this->getMock("r8\Curry\Unbound", array("rawExec"));
        $curry->setLeft("l1", "l2");
        $curry->setRight("r1", "r2");

        $curry->expects($this->once())
            ->method('rawExec')
            ->with( $this->equalTo( array("l1", "l2", 1, 2, 3, "r1", "r2") ) );

        $curry(1, 2, 3);
    }

    public function testInvokeWithSlicing ()
    {
        $curry = $this->getMock("r8\Curry\Unbound", array("rawExec"));
        $curry->setLeft("l1", "l2");
        $curry->setRight("r1", "r2");
        $curry->setOffset(2)->setLimit(2);

        $curry->expects($this->once())
            ->method('rawExec')
            ->with( $this->equalTo( array("l1", "l2", 3, 4, "r1", "r2") ) );

        $curry(1, 2, 3, 4, 5, 6);
    }

    public function testFilter ()
    {
        $curry = $this->getMock("r8\Curry\Unbound", array("rawExec"));

        $curry->expects($this->once())
            ->method('rawExec')
            ->with( $this->equalTo( array("wakka") ) );

        $curry->filter("wakka");
    }

    public function testFilterWithLeftRight ()
    {
        $curry = $this->getMock("r8\Curry\Unbound", array("rawExec"));
        $curry->setLeft("l1", "l2");
        $curry->setRight("r1", "r2");

        $curry->expects($this->once())
            ->method('rawExec')
            ->with( $this->equalTo( array("l1", "l2", 1, "r1", "r2") ) );

        $curry->filter(1);
    }

    public function testFilterWithZeroLimit ()
    {
        $curry = $this->getMock("r8\Curry\Unbound", array("rawExec"));
        $curry->setLeft("l1", "l2");
        $curry->setRight("r1", "r2");
        $curry->setLimit(0);

        $curry->expects($this->once())
            ->method('rawExec')
            ->with( $this->equalTo( array("l1", "l2", "r1", "r2") ) );

        $curry->filter(1);
    }

}

?>