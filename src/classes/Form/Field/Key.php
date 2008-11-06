<?php
/**
 * A hidden field used to help prevent XSRF attacks
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

namespace cPHP::Form::Field;

/**
 * A specialized hidden field used to help prevent XSRF attacks
 *
 * This works by generating a form "Key" for each user. The key is kept in a
 * hidden field. The form will only validate if the key in the form matches
 * the key generated by the server.
 *
 * The key generated is 20 characters long
 */
class Key extends ::cPHP::Form::Field::Hidden
{

    /**
     * Constructor...
     *
     * @param String $name The name of this form field
     * @param String $seed A random string that is used to help make the generated
     *      key unique to this specific instance
     */
    public function __construct( $name, $seed )
    {
        $this->setName( $name );

        $seed = ::cPHP::reduce($seed);
        if ( empty($seed) )
            throw new ::cPHP::Exception::Argument( 1, "Key Seed", "Must not be empty" );

        $key = substr( sha1( $seed . session_id() ), 0, 20 );

        $this->setValue( $key );

        $validator = new ::cPHP::Validator::Compare( "==", $key );
        $validator->addError("This form has expired");

        $this->setValidator( $validator );
    }

}

?>