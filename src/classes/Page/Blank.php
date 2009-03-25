<?php
/**
 * Page encapsulation class
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
 * @package Page
 */

namespace cPHP\Page;

/**
 * Displays a blank page
 */
class Blank implements \cPHP\iface\Page
{

    /**
     * Returns the core content this page will display
     *
     * @param cPHP\Page\Context $context A context object which is used by this
     *      page to communicate with the root page
     * @return \cPHP\Template\Blank Returns a blank template
     */
    public function getContent ( \cPHP\Page\Context $context )
    {
        return new \cPHP\Template\Blank;
    }

}

?>