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
 * @package Tag
 */

namespace r8\HTML;

/**
 * A Javascript source file
 */
class Javascript
{

    /**
     * The URL of the src file
     *
     * @var String
     */
    private $source;


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

}

?>