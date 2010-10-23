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
     * The conditional under which this css should be loaded
     *
     * @var \r8\HTML\Conditional
     */
    private $condition;

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
        r8(new \r8\Validator\URL( \r8\Validator\URL::ALLOW_RELATIVE ))->ensure( $source );
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
        $media = trim( (string) $media );
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
     * Returns the Condition under which this CSS should be loaded
     *
     * @return \r8\HTML\Conditional
     */
    public function getCondition ()
    {
        return $this->condition;
    }

    /**
     * Sets the Condition under which this CSS should be loaded
     *
     * @param \r8\HTML\Conditional $condition
     * @return \r8\HTML\CSS Returns a self reference
     */
    public function setCondition ( \r8\HTML\Conditional $condition )
    {
        $this->condition = $condition;
        return $this;
    }

    /**
     * Clears the Conditional from this instance
     *
     * @return \r8\HTML\CSS Returns a self reference
     */
    public function clearCondition ()
    {
        $this->condition = null;
        return $this;
    }

    /**
     * Builds a tag object from the data in this instance
     *
     * @return \r8\HTML\Tag
     */
    public function getTag ()
    {
        $tag = new \r8\HTML\Tag(
            'link',
            null,
            array(
                "rel" => "stylesheet",
                "href" => $this->source,
                "type" => "text/css",
                "media" => $this->media
            )
        );

        if ( $this->condition )
            return $this->condition->setContent( $tag );
        else
            return $tag;
    }

}

