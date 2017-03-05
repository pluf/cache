<?php
use PHPUnit\Framework\TestCase;
require_once 'Pluf.php';

class Cache_Api_MiddlewareTest extends TestCase
{

    /**
     * Can create new instance
     *
     * @test
     */
    public function instance ()
    {
        $middleware = new Cache_Middleware_RFC7234();
        $this->assertTrue(isset($middleware),
                'Impossible to create instance of Cache_Middleware_RFC7234');
    }

    /**
     * Check class api
     *
     * @test
     */
    public function methods ()
    {
        $middleware = new Cache_Middleware_RFC7234();
        $method_names = array(
                'process_request',
                'process_response'
        );
        foreach ($method_names as $method_name) {
            $this->assertTrue(method_exists($middleware, $method_name), 
                    'Method ' . $method_name .
                             ' not found in class Cache_Middleware_RFC7234');
        }
    }
}