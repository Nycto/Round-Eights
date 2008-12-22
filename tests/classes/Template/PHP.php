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
class classes_template_php extends PHPUnit_Extensions_OutputTestCase
{

    public $file;

    public function setUp ()
    {
        $this->file = rtrim( sys_get_temp_dir(), "/" ) ."/cPHP_". uniqid();
    }

    public function tearDown ()
    {
        @chmod( $this->file, 0777 );
        @unlink( $this->file );
    }

    public function testDisplay ()
    {
        file_put_contents(
                $this->file,
                '<?=$lorem ?> <?=$ipsum ?>'
            );

        $this->expectOutputString("Lorem Ipsum");

        $tpl = new \cPHP\Template\PHP;
        $tpl->setFinder( \cPHP\FileFinder::Dir()->addDir("/") );
        $tpl->setFile( $this->file );

        $tpl->set("lorem", "Lorem");
        $tpl->set("ipsum", "Ipsum");

        $tpl->display();
    }

    public function testDisplay_this ()
    {
        file_put_contents(
                $this->file,
                '<?=$lorem ?> <?=$var_this ?>'
            );

        $this->expectOutputString("Lorem Ipsum");

        $tpl = new \cPHP\Template\PHP;
        $tpl->setFinder( \cPHP\FileFinder::Dir()->addDir("/") );
        $tpl->setFile( $this->file );

        $tpl->set("lorem", "Lorem");
        $tpl->set("this", "Ipsum");

        $tpl->display();
    }

}

?>