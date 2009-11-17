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
 * @package HTML
 */

namespace r8\HTML;

/**
 * A CSS source file
 */
class CSS
{

    /**
     * The URL of the src file
     *
     * @var String
     */
    private $source;

    /**
     * The media type this CSS applies to
     *
     * @var String
     */
    private $media = "all";

    /**
     * Constructor...
     *
     * @param String $source The URL of the src file
     */
    public function __construct ( $source )
    {
        $this->setSource( $source );
    }

    /**
     * Sets the Source URL of this CSS resource
     *
     * @param String $source
     * @return \r8\HTML\CSS Returns a self reference
     */
    public function setSource ( $source )
    {
        \r8\Validator::URL( \r8\Validator\URL::ALLOW_RELATIVE )->ensure( $source );
        $this->source = $source;
        return $this;
    }

    /**
     * Returns the Source URL of this CSS resource
     *
     * @return String
     */
    public function getSource ()
    {
        return $this->source;
    }

    /**
     * Sets the Media type this CSS resource applies to
     *
     * @param String $media
     * @return \r8\HTML\CSS Returns a self reference
     */
    public function setMedia ( $media )
    {
        $media = trim( \r8\strval($media) );
        $this->media = empty($media) ? "all" : $media;
        return $this;
    }

    /**
     * Returns the Media type this css resource applies to
     *
     * @return String
     */
    public function getMedia ()
    {
        return $this->media;
    }

    /**
     * Builds a tag object from the data in this instance
     *
     * @return \r8\HTML\Tag
     */
    public function getTag ()
    {
        return new \r8\HTML\Tag(
            'link',
            null,
            array(
                "rel" => "stylesheet",
                "href" => $this->source,
                "type" => "text/css",
                "media" => $this->media
            )
        );
    }

}

?>