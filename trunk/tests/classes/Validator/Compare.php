<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_validator_compare extends PHPUnit_Framework_TestCase
{
    
    public function testBadOperator ()
    {
        try {
            $compare = new ::cPHP::Validator::Compare("bad", "value");
            $this->fail("An expected exception was not thrown");
        }
        catch( ::cPHP::Exception::Argument $err ) {
            $this->assertSame("Unsupported comparison operator", $err->getMessage());
        }
    }
    
    public function testLessThan ()
    {
        $valid = new ::cPHP::Validator::Compare( "<", 20 );

        $this->assertFalse( $valid->validate( 25 )->isValid() );
        $this->assertFalse( $valid->validate( 20 )->isValid() );
        $this->assertTrue( $valid->validate( 19 )->isValid() );
    }
    
    public function testGreaterThan ()
    {
        $valid = new ::cPHP::Validator::Compare( ">", 20 );

        $this->assertTrue( $valid->validate( 25 )->isValid() );
        $this->assertFalse( $valid->validate( 20 )->isValid() );
        $this->assertFalse( $valid->validate( 19 )->isValid() );
    }
    
    public function testLessThanEquals ()
    {
        $valid = new ::cPHP::Validator::Compare( "<=", 20 );

        $this->assertFalse( $valid->validate( 25 )->isValid() );
        $this->assertTrue( $valid->validate( 20 )->isValid() );
        $this->assertTrue( $valid->validate( 19 )->isValid() );
    }
    
    public function testGreaterThanEquals ()
    {
        $valid = new ::cPHP::Validator::Compare( ">=", 20 );

        $this->assertTrue( $valid->validate( 25 )->isValid() );
        $this->assertTrue( $valid->validate( 20 )->isValid() );
        $this->assertFalse( $valid->validate( 19 )->isValid() );
    }
    
    public function testSame ()
    {
        $valid = new ::cPHP::Validator::Compare( "===", 20 );

        $this->assertTrue(  $valid->validate( 20 )->isValid() );
        $this->assertFalse( $valid->validate( 25 )->isValid() );
        $this->assertFalse( $valid->validate( "20" )->isValid() );
    }
    
    public function testEquals ()
    {
        $valid = new ::cPHP::Validator::Compare( "==", 20 );

        $this->assertTrue(  $valid->validate( 20 )->isValid() );
        $this->assertTrue( $valid->validate( "20" )->isValid() );
        $this->assertFalse( $valid->validate( 25 )->isValid() );
        
        
        $valid = new ::cPHP::Validator::Compare( "=", 20 );

        $this->assertTrue(  $valid->validate( 20 )->isValid() );
        $this->assertTrue( $valid->validate( "20" )->isValid() );
        $this->assertFalse( $valid->validate( 25 )->isValid() );
    }
    
    public function testNotSame ()
    {
        $valid = new ::cPHP::Validator::Compare( "!==", 20 );

        $this->assertFalse( $valid->validate( 20 )->isValid() );
        $this->assertTrue(  $valid->validate( 25 )->isValid() );
        $this->assertTrue(  $valid->validate( "20" )->isValid() );
    }
    
    public function testNotEquals ()
    {
        $valid = new ::cPHP::Validator::Compare( "!=", 20 );

        $this->assertFalse( $valid->validate( 20 )->isValid() );
        $this->assertTrue(  $valid->validate( 25 )->isValid() );
        $this->assertFalse( $valid->validate( "20" )->isValid() );
        
        
        $valid = new ::cPHP::Validator::Compare( "<>", 20 );

        $this->assertFalse( $valid->validate( 20 )->isValid() );
        $this->assertTrue(  $valid->validate( 25 )->isValid() );
        $this->assertFalse( $valid->validate( "20" )->isValid() );
    }
    
}

?>