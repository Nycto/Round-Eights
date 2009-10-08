<?php
/**
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
 * @author James Frasca <James@RaindropPHP.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package XMLBuilder
 */

namespace h2o\Soap;

/**
 * Parses out the various parts of a soap request
 */
class Parser
{

    /**
     * The document to parse
     *
     * @var \DOMDocument
     */
    private $doc;

    /**
     * A shared xpath registered against the document being parsed
     *
     * @var \DOMXPath
     */
    private $xpath;

    /**
     * Constructor...
     *
     * @param \DOMDocument $doc The document to parse
     * @param String $namespace The namespace URI for the soap nodes
     */
    public function __construct ( \DOMDocument $doc, $namespace = "http://www.w3.org/2003/05/soap-envelope" )
    {
        $this->doc = $doc;

        $this->xpath = new \DOMXPath( $doc );
        $this->xpath->registerNamespace("soap", $namespace );
    }

    /**
     * Returns whether the document is empty
     *
     * @return Boolean
     */
    public function isEmpty ()
    {
        return !$this->doc->hasChildNodes();
    }

}

?>