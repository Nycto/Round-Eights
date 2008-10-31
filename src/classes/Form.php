<?php
/**
 * HTML Form Helper
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
 * @package Forms
 */

namespace cPHP;

/**
 * Collects a list of form fields and allows them to be manipulated as a group
 */
class Form implements Countable
{
    
    /**
     * Common submit methods
     */
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST'; 
    
    /**
     * The list of form fields
     */
    private $fields;
    
    /**
     * The URL this form will be submitted to
     *
     * The default value for this is the current URL, if there is one
     */
    private $action;
    
    /**
     * The submit method
     *
     * This defaults to 'POST'
     */
    private $method = self::METHOD_POST;
    
    /**
     * The form encoding
     */
    private $encoding = 'multipart/form-data';
    
    /**
     * Constructor... Sets the initial state of the instance
     */
    public function __construct ()
    {
        $this->fields = new ::cPHP::Ary;
    }
    
    /**
     * Returns the list of fields registered in this form
     *
     * @return Object Returns a cPHP::Ary object
     */
    public function getFields ()
    {
        return clone $this->fields;
    }
    
    /**
     * Adds a field to this instance
     *
     * @param Object $field The field being added
     * @return Object Returns a self reference
     */
    public function addField ( ::cPHP::iface::Form::Field $field )
    {
        // ensure there aren't any duplicates
        if ( !$this->fields->contains($field) )
            $this->fields[] = $field;
        
        return $this;
    }
    
    /**
     * Removes all the fields from this instance
     *
     * @return Object Returns a self reference
     */
    public function clearFields ()
    {
        $this->fields->clear();
        return $this;
    }
    
    /**
     * Returns the number of fields in this instance
     *
     * @return Integer
     */
    public function count ()
    {
        return $this->fields->count();
    }
    
    /**
     * Returns the first field with the given name
     *
     * @param String $name The name of the field to return
     * @return Boolean|Object Returns the requested field, or FALSE if it can't be found
     */
    public function find ( $name )
    {
        $name = ::cPHP::Filter::Variable()->filter( $name );
        
        if ( !::cPHP::Validator::Variable()->isValid( $name ) )
            throw new ::cPHP::Exception::Argument( 0, "Field Name", "Must be a valid PHP variable name" );
        
        return $this->fields->find(function($field) use ( $name ) {
            return $field->getName() == $name ? TRUE : FALSE;
        });
    }
    
}

?>