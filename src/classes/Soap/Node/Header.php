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
 * @package XMLBuilder
 */

namespace r8\Soap\Node;

/**
 * A Soap Header Node
 */
class Header extends \r8\Soap\Node
{

    /**
     * Returns the Role URI of this header
     *
     * @return String
     */
    public function getRole ()
    {
        if ( $this->node->hasAttributeNS( $this->soapNS, "role" ) )
            return $this->node->getAttributeNS( $this->soapNS, "role" );
        else
            return NULL;
    }

    /**
     * Returns whether understanding this header is required
     *
     * @return Boolean
     */
    public function mustUnderstand ()
    {
        if ( !$this->node->hasAttributeNS( $this->soapNS, "mustUnderstand" ) )
            return FALSE;

        $value = $this->node->getAttributeNS( $this->soapNS, "mustUnderstand" );

        return \r8\Filter::Boolean()->filter( $value );
    }

}

?>