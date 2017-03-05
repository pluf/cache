<?php
use PHPUnit\Framework\TestCase;
require_once 'Pluf.php';

class Cache_Api_ResponseTest extends TestCase
{

    /**
     * Check if it is not reusable view
     *
     * @test
     */
    public function notReusableView ()
    {
        $query = '/example/resource';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = 'http://localhost/example/resource';
        $_SERVER['REMOTE_ADDR'] = 'not set';
        $_SERVER['HTTP_HOST'] = 'localhost';
        $GLOBALS['_PX_uniqid'] = 'example';
        
        $middleware = new Cache_Middleware_RFC7234();
        $request = new Pluf_HTTP_Request($query);
        $response = new Pluf_HTTP_Response('Hi!');
        
        // empty view
        $request->view = array();
        
        $response = $middleware->process_response($request, $response);
        $this->assertTrue(array_key_exists('Cache-Control', $response->headers),
                '\'Cache-Control\' not found in the header.');
        $this->assertTrue(
                strrpos($response->headers['Cache-Control'], 'no-cache') != false, 
                'The \'no-cache\' phrase not exist in header \'Cache-Control\'.');
    }
}