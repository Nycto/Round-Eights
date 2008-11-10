<?php
/**
 * URI object
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
 * @package Tag
 */

namespace cPHP;

/**
 * Class for parsing, manipulating and outputting URIs
 */
class Uri
{

    /**
     * The protocol for this link
     */
    private $scheme;

    /**
     * The username for this URL
     */
    private $username;

    /**
     * The password in this URL
     */
    private $password;

    /**
     * The subdomain for a link
     */
    private $subdomain;

    /**
     * The second level domain name for a link.
     *
     * This is the chunk just to the left of the TLD. For example, in
     * "www.example.com", "example" is the sld.
     */
    private $sld;

    /**
     * The TLD (top level domain) for a link. For example: com, net, org, or gov.
     */
    private $tld;

    /**
     * The port for a link.
     *
     * If it isn't set as an integer, it won't be used
     */
    private $port;

    /**
     * The directory path for a link
     */
    private $directory;

    /**
     * The filename for a link
     */
    private $filename;

    /**
     * The extension of the filename for this path
     */
    private $extension;

    /**
     * Query variables for this link
     */
    private $query = Array();

    /**
     * Fragment for this link
     */
    private $fragment;

    /**
     * Returns the singelton Env instance
     *
     * This method exists strictly for unit testing purposes. By mocking this
     * method you can feed a spoof environment to the rest of the instance
     *
     * @return Object Returns a ::cPHP::Env instance
     */
    protected function getEnv ()
    {
        return Env::get();
    }

    /**
     * Returns the scheme for this instance
     *
     * If no specific scheme has been set, it will return the scheme useed to
     * fetch the current page. Failing that (for example, running via the command
     * line), the default is "http"
     *
     * @return String Returns the scheme for this link
     */
    public function getScheme ()
    {

        if ( isset($this->scheme) )
            return $this->scheme;

        // Pull the scheme from the environment
        $env = $this->getEnv();

        if ( isset( $env->scheme ) )
            return $env->scheme;

        else
            return "http";

    }

    /**
     * Sets the scheme for this instance
     *
     * @param String $scheme
     * @return Object Returns a self reference
     */
    public function setScheme ( $scheme )
    {
        $scheme = strtolower( ::cPHP::str::stripW($scheme) );

        if ( empty($scheme) )
            throw new ::cPHP::Exception::Argument(0, "Uri Scheme", "Must not be empty");

        $this->scheme = $scheme;
        
        return $this;
    }

}

?>