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
 * @package HTML
 */

namespace r8\HTML;

/**
 * An HTML MetaTag
 */
class MetaTag
{

    /**
     * The name of this meta tag
     *
     * @var String
     */
    private $name;

    /**
     * The content of this meta tag
     *
     * @var String
     */
    private $content;

    /**
     * Constructor...
     *
     * @param
     */
    public function __construct ( $name, $content )
    {
        $this->setName( $name );
        $this->setContent( $content );
    }

    /**
     * Sets the Name of this MetaTag
     *
     * @param String $name
     * @return \r8\HTML\MetaTag Returns a self reference
     */
    public function setName ( $name )
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Returns the Name of this Meta Tag
     *
     * @return String
     */
    public function getName ()
    {
        return $this->name;
    }

    /**
     * Sets the Content of this MetaTag
     *
     * @param String $content
     * @return \r8\HTML\MetaTag Returns a self reference
     */
    public function setContent ( $content )
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Returns the Content of this MetaTag
     *
     * @return String
     */
    public function getContent ()
    {
        return $this->content;
    }

    /**
     * Builds a tag object from the data in this instance
     *
     * @return \r8\HTML\Tag
     */
    public function getTag ()
    {
        return new \r8\HTML\Tag(
            'meta',
            null,
            array(
                "name" => $this->name,
                "content" => $this->content
            )
        );
    }

}

?>