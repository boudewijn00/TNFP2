<?php


namespace Tnfp;

use Tnfp\FooClass;

class FooTest extends \PHPUnit_Framework_TestCase {
    
    public function testFoo(){
        
        $foo = new FooClass("bob");
        $this->assertEquals($foo->name,"bob");
        
        
    }
    
}