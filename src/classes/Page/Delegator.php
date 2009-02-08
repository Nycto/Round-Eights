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
 * Base class for page objects that select their display from a set of options
 */
abstract class Delegator extends \cPHP\Page
{

    /**
     * The name of the method to display
     *
     * @var String
     */
    private $view = 'index';

    /**
     * Constructor... Allows you to set the source on construction
     *
     * @param String $view The name of the method to use for rendering the page
     */
    public function __construct( $view = NULL )
    {
        if ( !\cPHP\isVague($view) )
            $this->setView( $view );
    }

    /**
     * Sets the method that will be used to render this page
     *
     * @param String $view The name of the method to use for rendering the page
     * @return Object Returns a self reference
     */
    public function setView ( $view )
    {
        $view = \cPHP\indexVal( $view );

        if ( \cPHP\isEmpty($view) )
            throw new \cPHP\Exception\Argument(0, 'View Index', 'Must not be empty');

        $this->view = $view;

        return $this;
    }

    /**
     * Returns the method that this instance will attempt to use as the renderer
     *
     * @return String|Null Returns the view. This does not guarantee that the
     *    view is defined, just that this view is the one selected. Returns NULL
     *    if no view exists.
     */
    public function getView ()
    {
        return $this->view;
    }

    /**
     * Clears the view from this instance
     *
     * @return Object Returns a self reference
     */
    public function resetView ()
    {
        $this->view = 'index';
        return $this;
    }

    /**
     * Pulls the method that will be used to render this page from an index in
     * an array
     *
     * @param String $index The index to pull
     * @param mixed $source The source array
     * @return Object Returns a self reference
     */
    public function setViewFrom ( $index, $source )
    {
        if ( !($source instanceof \ArrayAccess ) )
            $source = new \cPHP\Ary( $source );

        if ( isset($source[$index]) ) {
            try {
                $this->setView( $source[$index] );
            }
            catch ( \cPHP\Exception\Argument $err ) {}
        }

        return $this;
    }

    /**
     * Returns whether the current view is defined
     *
     * @return Boolean Returns whether the current view is defined
     */
    abstract public function viewExists ();

    /**
     * Returns the content to display if an invalid view is given
     *
     * @return Object Returns a template object
     */
    public function getErrorView ()
    {
        return new \cPHP\Template\Raw("View does not exist");
    }

}

?>