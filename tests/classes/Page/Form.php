<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of commonPHP.
 *
 * commonPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * commonPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with commonPHP. If not, see <http://www.commonphp.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@commonphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_page_form extends PHPUnit_Framework_TestCase
{

    public function getTestObj ()
    {
        return $this->getMock(
                'cPHP\Page\Form',
                array('createForm')
            );
    }

    public function testGetForm ()
    {
        $form = new \cPHP\Form;

        $page = $this->getTestObj();
        $page->expects( $this->once() )
            ->method( 'createForm' )
            ->will( $this->returnValue($form) );

        $this->assertSame( $form, $page->getForm() );
        $this->assertSame( $form, $page->getForm() );
        $this->assertSame( $form, $page->getForm() );
    }

    public function testGetForm_invalid ()
    {
        $page = $this->getTestObj();
        $page->expects( $this->once() )
            ->method( 'createForm' )
            ->will( $this->returnValue('non form') );

        $form = $page->getForm();

        $this->assertThat( $form, $this->isInstanceOf('cPHP\Form') );

        $this->assertSame( $form, $page->getForm() );
        $this->assertSame( $form, $page->getForm() );
        $this->assertSame( $form, $page->getForm() );
    }

}

?>