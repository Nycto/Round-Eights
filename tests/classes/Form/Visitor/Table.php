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
class classes_Form_Visitor_Table extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a test form
     *
     * @return \r8\Form
     */
    public function getTestForm ()
    {
        $form = new \r8\Form;
        $form->setAction("http://www.example.com/submit.php");
        $form->andFormValidator( new \r8\Validator\Fail("Form Error") );

        $form->addField(
            r8( new \r8\Form\Checkbox("CheckboxFld", "Checkbox Label") )
                ->andValidator( new \r8\Validator\Fail("Checkbox Error") )
        );
        $form->addField( new \r8\Form\File("FileFld", "File Label") );
        $form->addField( r8( new \r8\Form\Hidden("HiddenFld") ) );
        $form->addField(
            r8( new \r8\Form\Password("PasswordFld", "Password Label") )
                ->andValidator( new \r8\Validator\Fail("Password Error") )
        );
        $form->addField(
            r8( new \r8\Form\Radio("RadioFld", "Radio Label") )
                ->addOption( 1234, "Radio Option" )
                ->andValidator( new \r8\Validator\Fail("Radio Error") )
        );
        $form->addField(
            r8( new \r8\Form\Select("SelectFld", "Select Label") )
                ->andValidator( new \r8\Validator\Fail("Select Error") )
        );
        $form->addField(
            r8( new \r8\Form\Text("TextFld", "Text Label") )
                ->andValidator( new \r8\Validator\Fail("Text Error") )
        );
        $form->addField(
            r8( new \r8\Form\TextArea("TextAreaFld", "TextArea Label") )
                ->andValidator( new \r8\Validator\Fail("TextArea Error") )
        );

        return $form;
    }

    public function testVisit_WithoutErrors ()
    {
        $form = $this->getTestForm();

        $tpl = $form->visit( new \r8\Form\Visitor\Table(
            FALSE,
            "Form Title",
            "Submit Button"
        ) );

        $result = $tpl->render();

        $this->assertContains( 'http://www.example.com/submit.php', $result );
        $this->assertContains( 'HiddenFld', $result );
        $this->assertContains( 'Form Title', $result );
        $this->assertContains( 'Checkbox Label', $result );
        $this->assertContains( 'name="CheckboxFld"', $result );
        $this->assertContains( 'File Label', $result );
        $this->assertContains( 'name="FileFld"', $result );
        $this->assertContains( 'Password Label', $result );
        $this->assertContains( 'name="PasswordFld"', $result );
        $this->assertContains( 'Radio Label', $result );
        $this->assertContains( 'name="RadioFld"', $result );
        $this->assertContains( 'Select Label', $result );
        $this->assertContains( 'name="SelectFld"', $result );
        $this->assertContains( 'Text Label', $result );
        $this->assertContains( 'name="TextFld"', $result );
        $this->assertContains( 'TextArea Label', $result );
        $this->assertContains( 'name="TextAreaFld"', $result );
        $this->assertContains( 'Submit Button', $result );

        $this->assertNotContains( "Form Error", $result );
        $this->assertNotContains( "Checkbox Error", $result );
        $this->assertNotContains( "Password Error", $result );
        $this->assertNotContains( "Radio Error", $result );
        $this->assertNotContains( "Select Error", $result );
        $this->assertNotContains( "Text Error", $result );
        $this->assertNotContains( "TextArea Error", $result );
    }

    public function testVisit_WithErrors ()
    {
        $form = $this->getTestForm();

        $tpl = $form->visit( new \r8\Form\Visitor\Table(
            TRUE,
            "Form Title",
            "Submit Button"
        ) );

        $result = $tpl->render();

        $this->assertContains( "Form Error", $result );
        $this->assertContains( "Checkbox Error", $result );
        $this->assertContains( "Password Error", $result );
        $this->assertContains( "Radio Error", $result );
        $this->assertContains( "Select Error", $result );
        $this->assertContains( "Text Error", $result );
        $this->assertContains( "TextArea Error", $result );
    }

}

?>