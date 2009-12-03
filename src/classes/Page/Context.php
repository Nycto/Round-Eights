<?php
/**
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
 * @package Page
 */

namespace r8\Page;

/**
 * Allows pages to communicate with the root page, even if they are nested inside
 * other pages
 */
class Context
{

    /**
     * Whether the overall result of this page should be suppressed
     *
     * @var Boolean
     */
    private $suppressed = FALSE;

    /**
     * If a page requests a redirect, the URL to point to is saved here
     *
     * @var String
     */
    private $redirect;

    /**
     * Indicates to the root page that the rendered content should not be displayed
     *
     * @return \r8\Page\Context Returns a self reference
     */
    public function suppress ()
    {
        $this->suppressed = TRUE;
        return $this;
    }

    /**
     * Returns whether the content should be suppressed
     *
     * The root page will look at this value. If it is true, a blank template is
     * returned.
     *
     * @return Boolean
     */
    public function isSuppressed ()
    {
        return $this->suppressed;
    }

    /**
     * Helper function that redirects the client to another url
     *
     * If redirect is called twice, the second call will override the first call
     *
     * @param String $url The URL to forward them to
     * @return \r8\Page\Context Returns a self reference
     */
    public function redirect ( $url )
    {
        $url = trim( \r8\strval( $url ) );

        \r8\Validator::URL( \r8\Validator\URL::ALLOW_RELATIVE )->ensure( $url );

        $this->redirect = $url;

        $this->suppress();

        return $this;
    }

    /**
     * Returns the URL the page will be redirected to
     *
     * @return Null|String Returns NULL if no redirect has been set
     */
    public function getRedirect ()
    {
        return $this->redirect;
    }

    /**
     * Immediately interrupts the page load and returns control to the root page
     *
     * This causes an interrupt exception to be thrown. The root page will react
     * by catching the exception and returning a blank template
     *
     * @throws \r8\Exception\Interrupt\Page
     * @return null
     */
    public function interrupt ()
    {
        throw new \r8\Exception\Interrupt\Page("Page execution interrupted");
    }

}

?>