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
 * unit tests
 */
class classes_Page_Head extends PHPUnit_Framework_TestCase
{

    public function testGetContent_Empty ()
    {
        $context = $this->getMock('\r8\Page\Context');

        $wrapped = $this->getMock('\r8\iface\Page');
        $wrapped->expects( $this->once() )
            ->method( "getContent" )
            ->with( $this->equalTo($context) )
            ->will( $this->returnValue( new \r8\Template\Blank ) );

        $page = new \r8\Page\Head(
            new \r8\HTML\Head,
            $wrapped
        );

        $result = $page->getContent( $context );

        $this->assertThat( $result, $this->isInstanceOf('\r8\iface\Template') );
        $this->assertSame(
            '<html>' ."\n"
            .'<head></head>' ."\n"
            .'<body>' ."\n\n"
            .'</body>' ."\n"
            .'</html>',
            $result->render()
        );
    }

    public function testGetContent_Full ()
    {
        $context = $this->getMock('\r8\Page\Context');

        $wrapped = $this->getMock('\r8\iface\Page');
        $wrapped->expects( $this->once() )
            ->method( "getContent" )
            ->with( $this->equalTo($context) )
            ->will( $this->returnValue( new \r8\Template\Raw("Page Content") ) );

        $head = new \r8\HTML\Head;
        $head->setDocType( \r8\HTML\DocType::HTML5() );
        $head->setTitle("Test");
        $head->addCSS( new \r8\HTML\CSS("test.css") );
        $head->addCSS( new \r8\HTML\CSS("example.css") );
        $head->addJavascript( new \r8\HTML\Javascript("test.js") );
        $head->addJavascript( new \r8\HTML\Javascript("example.js") );
        $head->addMetaTag( new \r8\HTML\MetaTag("name", "content") );
        $head->addMetaTag( new \r8\HTML\MetaTag("robots", "index, follow") );

        $page = new \r8\Page\Head( $head, $wrapped );

        $result = $page->getContent( $context );

        $this->assertThat( $result, $this->isInstanceOf('\r8\iface\Template') );
        $this->assertSame(
            '<!DOCTYPE html>' ."\n"
            .'<html>' ."\n"
            .'<head>'
            .'<title>Test</title>' ."\n"
            .'<meta name="name" content="content" />' ."\n"
            .'<meta name="robots" content="index, follow" />' ."\n"
            .'<link rel="stylesheet" href="test.css" type="text/css" media="all" />' ."\n"
            .'<link rel="stylesheet" href="example.css" type="text/css" media="all" />' ."\n"
            .'<script type="text/javascript" src="test.js"></script>' ."\n"
            .'<script type="text/javascript" src="example.js"></script>'
            .'</head>' ."\n"
            .'<body>' ."\n"
            .'Page Content' ."\n"
            .'</body>' ."\n"
            .'</html>',
            $result->render()
        );
    }

}

?>