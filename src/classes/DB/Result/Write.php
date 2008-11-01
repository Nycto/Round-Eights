<?php
/**
 * Database Query Result
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
 * @package Database
 */

namespace cPHP::DB::Result;

/**
 * Database Read Query Results
 */
class Write extends ::cPHP::DB::Result
{

    /**
     * This is the cached value of the affected number of rows
     */
    private $affected;

    /**
     * This is the cached value of the insert ID
     */
    private $insertID;

    /**
     * Constructor...
     *
     * @param Integer|NULL $affected The number of rows affected by this query
     * @param Integer|NULL $insertID The ID of the row inserted by this query
     * @param String $query The query that produced this result
     */
    public function __construct ( $affected, $insertID, $query )
    {
        if ( !::cPHP::is_vague($insertID) ) {
            $insertID = intval($insertID);
            $this->insertID = $insertID > 0 ? $insertID : NULL;
        }

        $this->affected = max( intval( $affected ), 0 );

        parent::__construct($query);
    }

    /**
     * Returns the number of rows affected by a query
     *
     * @return Integer|False
     */
    public function getAffected ()
    {
        return $this->affected;
    }

    /**
     * Returns the ID of the row inserted by this query
     *
     * @return Integer|False This will return FALSE if no ID is returned
     */
    public function getInsertID ()
    {
        return $this->insertID;
    }

}

?>