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
 * @package Filters
 */

namespace cPHP\Page;

/**
 * Decides which method to use based on a string
 *
 * Views are defined by extending this class and defining methods. Any method
 * that beings with 'view_' will be callable.
 */
abstract class Method extends \cPHP\Page\Delegator
{

    /**
     * Returns whether the current view is defined
     *
     * @return Boolean Returns whether the current view is defined
     */
    public function viewExists ()
    {
        return method_exists( $this, 'view_'. $this->getView() );
    }

    /**
     * Executes the view method and returns it's results
     *
     * @return Object Returns a template object
     */
    public function createContent ()
    {
        if ( !$this->viewExists() )
            return $this->getErrorView();

        $method = 'view_'. $this->getView();

        return $this->$method();
    }

}

?>