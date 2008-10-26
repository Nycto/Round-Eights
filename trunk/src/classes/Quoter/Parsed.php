<?php
/**
 * Quote parsing result class
 *
 * PHP version 5.3
 *
 * This source file is subject to version 2.0 of the Artistic License. A copy
 * of the license should have been bundled with this source file inside a file
 * named LICENSE.txt. It is also available through the world-wide-web at one
 * of the following URIs:
 * http://www.commonphp.com/license.php
 * http://www.opensource.org/licenses/artistic-license-2.0.php
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @author James Frasca <james@commonphp.com>
 * @license Artistic License 2.0 http://www.commonphp.com/license.php
 * @package Quoter
 */

namespace cPHP::Quoter;

/**
 * Representation a collection of parsed string sections
 */
class Parsed
{
    
    /**
     * The sections represented by this instance
     */
    private $sections = array();
    
    /**
     * Whether the iterator functionality should include the quoted objects
     */
    private $quoted = TRUE;
    
    /**
     * Whether the iterator functionality should include the unquoted objects
     */
    private $unquoted = TRUE;
    
    /**
     * Returns a list of all the sections in this instance
     *
     * @return Object Returns a cPHP::Ary object
     */
    public function getSections ()
    {
        return new ::cPHP::Ary( $this->sections );
    }
    
    /**
     * Adds a new section to the end of this list
     *
     * @param Object $section The section being added
     * @return Object Returns a self reference
     */
    public function addSection( cPHP::Quoter::Section $section )
    {
        $this->sections[] = $section;
        return $this;
    }
    
    /**
     * Converts all the contained sections to strings and concatenates them
     *
     * @return String
     */
    public function __toString ()
    {
        $result = "";
        foreach ( $this->sections AS $section ) {
            $result .= $section->__toString();
        }
        return $result;
    }
    
    /**
     * Returns whether the quoted values will included in the advanced functionality
     *
     * @return Boolean
     */
    public function getIncludeQuoted ()
    {
        return $this->quoted;
    }
    
    /**
     * Sets whether the quoted values should be included in the advanced functionality
     *
     * @return Object returns a self reference
     */
    public function setIncludeQuoted ( $setting )
    {
        $this->quoted = $setting ? TRUE : FALSE;
        return $this;
    }
    
    /**
     * Returns whether the unquoted values will included in the advanced functionality
     *
     * @return Boolean
     */
    public function getIncludeUnquoted ()
    {
        return $this->unquoted;
    }
    
    /**
     * Sets whether the unquoted values should be included in the advanced functionality
     *
     * @return Object returns a self reference
     */
    public function setIncludeUnquoted ( $setting )
    {
        $this->unquoted = $setting ? TRUE : FALSE;
        return $this;
    }
    
    /**
     * Splits the string based on a separator and returns the resulting sections
     *
     * This will only explode on the separators found in the selected section.
     *
     * @param String $separator The separator to split the string on
     * @return Object Returns a cPHP::Ary object containing the string sections
     */
    public function explode ( $separator )
    {
        $result = new ::cPHP::Ary(array(""));
        
        foreach ( $this->sections AS $section ) {
            
            if ( ( $section->isQuoted() && $this->quoted ) || ( !$section->isQuoted() && $this->unquoted ) )
                $exploded = explode( $separator, $section->__toString() );
            else
                $exploded = array( $section->__toString() );
            
            $result->push(
                    $result->pop( TRUE ) . array_shift( $exploded )
                );
            
            $result = $result->merge( $exploded );
            
        }
        
        return $result;
    }
    
    /**
     * Applies a filter to the selected sections
     *
     * @param Object $filter The filter to apply
     * @return Object Returns a self reference
     */
    public function filter ( ::cPHP::iface::Filter $filter )
    {
        if ( !$this->quoted && !$this->unquoted )
            return $this;
        
        foreach ( $this->sections AS $section ) {
            
            if ( ( $section->isQuoted() && $this->quoted ) || ( !$section->isQuoted() && $this->unquoted ) ) {
                
                $section->setContent(
                        $filter->filter( $section->getContent() )
                    );
                
            }
            
        }
        
        return $this;
    }
    
}

?>