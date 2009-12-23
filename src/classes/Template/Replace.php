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
 * @copyright Copyright 2009, James Frasca, All Rights Reserved
 * @package Template
 */

namespace r8\Template;

/**
 * A basic search and replace template
 *
 * The default replacement string is #{label}
 */
class Replace extends \r8\Template\Access implements \r8\iface\Template\Access
{

    /**
     * The search string to replace
     *
     * @var String
     */
    private $search = '/(\\\\*)(#\{(.*?)\})/';

    /**
     * The template string that will be rendered
     *
     * @var String
     */
    private $template;

    /**
     * Constructor...
     *
     * @param String $template The template string that will be rendered
     * @param mixed $import An array, object, or another template to use as the
     *      source data. See the import method for more information.
     */
    public function __construct ( $template, $import = null )
    {
        $this->setTemplate( $template );
        if ( !is_null($import) )
            $this->import( $import );
    }

    /**
     * Returns the template string that will be rendered
     *
     * @return String The template string that will be rendered
     */
    public function getTemplate ()
    {
        return $this->template;
    }

    /**
     * Sets the template that will be rendered
     *
     * @param String $template The template string
     * @return Object Returns a self reference
     */
    public function setTemplate ( $template )
    {
        $this->template = \r8\strval( $template );
        return $this;
    }

    /**
     * Returns the regular expression that will be used to find replacements
     *
     * @return String Returns the search string
     */
    public function getSearch ()
    {
        return $this->search;
    }

    /**
     * Sets the regular expression that will be used to find replacements
     *
     * To make sure escaping works, this regular expression must return three
     * groupings. The first is to capture any preceding escape characters. The
     * second will capture the string to be replaced. The third group captures
     * the label to lookup the new value.
     *
     * @param String $search The search string
     * @return Object Returns a self reference
     */
    public function setSearch ( $search )
    {
        $this->search = \r8\strval( $search );
        return $this;
    }

    /**
     * Renders this template and outputs it to the client
     *
     * @return Object Returns a self reference
     */
    public function display ()
    {
        echo $this->render();
        return $this;
    }

    /**
     * Renders the template and returns it as a string
     *
     * @return String Returns the rendered template as a string
     */
    public function render ()
    {
        $self = $this;

        return preg_replace_callback( $this->search, function ($matches) use ($self) {

            // Ensure that the search string returns the proper number of matches
            if ( count($matches) < 4 ) {
                $err = new \r8\Exception\Data(
                        $self->search,
                        "Search Regular Expression",
                        "Must return at least 3 groupings"
                    );
                $err->addData("Groupings", count($matches) - 1);
                throw $err;
            }

            // Handle escaped replace strings
            if ( strlen($matches[1]) % 2 != 0 )
                return substr( $matches[1], 0, -1 ) .$matches[2];

            // If this variable isn't set or this replacement would cause recursion, return blank
            else if ( !$self->exists( $matches[3] ) || $self->get( $matches[3] ) === $self )
                return $matches[1];

            // Otherwise make the replacement
            else
                return $matches[1] . \r8\strval( $self->get( $matches[3] ) );

        }, $this->template );
    }

    /**
     * Renders the template and returns it as a string
     *
     * @return String
     */
    public function __toString ()
    {
        return $this->render();
    }

}

?>