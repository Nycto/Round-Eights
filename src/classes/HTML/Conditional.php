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
 * An HTML conditional comment
 */
class Conditional extends \r8\HTML\Node
{

    /**
     * The condition that this instance represents
     *
     * @var String
     */
    private $condition;

    /**
     * Constructor...
     *
     * @param String $condition The condition this instance represents
     * @param String $content Any content for this instance
     */
    public function __construct ( $condition, $content = null )
    {
        parent::__construct( $content );
        $this->setCondition( $condition );
    }

    /**
     * Sets the value of the condition in this instance
     *
     * @param String $condition The condition this instance represents
     * @return \r8\HTML\Conditional Returns a self reference
     */
    public function setCondition ( $condition )
    {
        $condition = trim( \r8\str\stripW($condition, " ") );

        if ( \r8\isEmpty($condition) )
            throw new \r8\Exception\Argument(0, "Condition", "Must not be empty");

        $this->condition = $condition;

        return $this;
    }

    /**
     * Returns the Condition in this instance
     *
     * @return String
     */
    public function getCondition ()
    {
        return $this->condition;
    }

    /**
     * Returns a string representation of the open part of the condition
     *
     * This will return something like: <!--[if gte IE 7]>
     *
     * @return String An opening conditional HTML comment condition
     */
    public function getOpenTag ()
    {
        return '<!--[if '. $this->condition .']>';
    }

    /**
     * Returns the html to close this condition
     *
     * This will return something like: <![endif]-->
     *
     * @return String A closing conditional HTML comment condition
     */
    public function getCloseTag ()
    {
        return '<![endif]-->';
    }

    /**
     * Returns the HTML string represented by this instance
     *
     * @return String Returns a string of HTML
     */
    public function render ()
    {
        return $this->getOpenTag() . $this->getContent() . $this->getCloseTag();
    }

}

?>