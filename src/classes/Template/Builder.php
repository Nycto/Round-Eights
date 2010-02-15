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
 * A helper object for building various templates
 */
class Builder extends \r8\Template\Access
{

    /**
     * The Finder to load into any file based templates
     *
     * @var \r8\Finder
     */
    private $finder;

    /**
     * Constructor...
     *
     * @param \r8\Finder $finder The Finder to load into any file based templates
     */
    public function __construct ( \r8\Finder $finder )
    {
        $this->finder = $finder;
    }

    /**
     * Builds a new blank template
     *
     * @return \r8\Template\Blank
     */
    public function blank ()
    {
        return new \r8\Template\Blank;
    }

    /**
     * Returns a new Collection template
     *
     * @return \r8\Template\Collection
     */
    public function collection ()
    {
        return new \r8\Template\Collection;
    }

    /**
     * Builds a new DOMDocument template
     *
     * @param \DOMDocument $doc The XML document being rendered
     * @return \r8\Template\DOMDoc
     */
    public function domDoc ( \DOMDocument $doc )
    {
        return new \r8\Template\DOMDoc( $doc );
    }

    /**
     * Builds a new Raw template
     *
     * @param mixed $content The content for this instance
     * @return \r8\Template\Raw
     */
    public function raw ( $content = NULL )
    {
        return new \r8\Template\Raw( $content );
    }

    /**
     * Builds a new Replace template
     *
     * @param String $template The template string that will be rendered
     * @return \r8\Template\Replace
     */
    public function replace ( $template )
    {
        $tpl = new \r8\Template\Replace( $template );
        $tpl->import( $this->getValues() );
        return $tpl;
    }

    /**
     * Builds a new PHP file template
     *
     * @param mixed $file The file this tempalte should load
     * @return \r8\Template\PHP
     */
    public function php ( $file )
    {
        $tpl = new \r8\Template\PHP( $this->finder, $file );
        $tpl->import( $this->getValues() );
        return $tpl;
    }

    /**
     * Builds a new Iterate template
     *
     * @param \r8\iface\Template\Access $prototype The prototype template to clone
     * @param \Traversable $iterator The iterator to pull data from
     * @return \r8\Template\Iterate
     */
    public function iterate (
        \r8\iface\Template\Access $prototype,
        \Traversable $iterator
    ) {
        return new \r8\Template\Iterate( $prototype, $iterator );
    }

}

?>