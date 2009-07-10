<?php
/**
 * Page encapsulation class
 *
 * @license Artistic License 2.0
 *
 * This file is part of RaindropPHP.
 *
 * RaindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * RaindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with RaindropPHP. If not, see <http://www.RaindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@Raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Page
 */

namespace h2o\Page;

/**
 * Takes an input, converts it to a string, and presents it as a page
 */
class Raw implements \h2o\iface\Page
{

    /**
     * The content this page will display
     *
     * @var String
     */
    private $data;

    /**
     * Constructor...
     *
     * @param mixed $data The data for this instance
     */
    public function __construct( $data = NULL )
    {
        $this->setData( $data );
    }

    /**
     * Returns the data in this instance
     *
     * @return mixed The data
     */
    public function getData ()
    {
        return $this->data;
    }

    /**
     * Sets the data for this instance
     *
     * @param mixed $data The data being set
     * @return Object Returns a self reference
     */
    public function setData ( $data )
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Clears the data out of this instance
     *
     * @return Boolean Returns whether this instance has any data
     */
    public function dataExists ()
    {
        return isset( $this->data );
    }

    /**
     * Clears the data out of this instance
     *
     * @return Object Returns a self reference
     */
    public function clearData ()
    {
        $this->data = null;
        return $this;
    }

    /**
     * Returns the core content this page will display
     *
     * @param \h2o\Page\Context $context A context object which is used by this
     *      page to communicate with the root page
     * @return \h2o\Template\Raw Returns the template for this page
     */
    public function getContent ( \h2o\Page\Context $context )
    {
        return new \h2o\Template\Raw( $this->data );
    }

}

?>