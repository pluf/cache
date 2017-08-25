<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. http://dpq.co.ir
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
use PHPUnit\Framework\TestCase;
require_once 'Pluf.php';

/**
 * Response test class
 */
class CacheTestHttpResponse extends Pluf_HTTP_Response
{

    function __construct ($content = '', $mimetype = null)
    {
        parent::__construct($content, $mimetype);
    }

    public function etag ()
    {
        return 'test';
    }
}

/**
 * Check middleware api
 *
 * @author pluf.ir<info@pluf.ir>
 *        
 */
class Cache_Api_ResponseTest extends TestCase
{

    /**
     * Check if there is no cache config
     *
     * @test
     */
    public function notConfiguredView ()
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
        $request->view = array(
                'ctrl' => array()
        );
        
        $response = $middleware->process_response($request, $response);
        $this->assertTrue(array_key_exists('Cache-Control', $response->headers), 
                '\'Cache-Control\' not found in the header.');
        $this->assertTrue(
                strrpos($response->headers['Cache-Control'], 'no-store') !==
                         false, 
                        'The \'no-store\' phrase not exist in header \'Cache-Control\'.');
    }

    /**
     * @test
     */
    public function checkCacheableView1 ()
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
        $request->view = array(
                'ctrl' => array(
                        'cacheable' => false,
                        'revalidate' => false,
                        'intermediate_cache' => false,
                        'max_age' => 100
                )
        );
        
        $response = $middleware->process_response($request, $response);
        $this->assertTrue(array_key_exists('Cache-Control', $response->headers), 
                '\'Cache-Control\' not found in the header.');
        $this->assertTrue(
                strrpos($response->headers['Cache-Control'], 'no-store') !==
                         false, 
                        'The \'no-store\' phrase not exist in header \'Cache-Control\'.');
    }

    /**
     * @test
     */
    public function checkCacheableView2 ()
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
        $request->view = array(
                'ctrl' => array(
                        'cacheable' => true,
                        'revalidate' => false,
                        'intermediate_cache' => false,
                        'max_age' => 100
                )
        );
        
        $response = $middleware->process_response($request, $response);
        $this->assertTrue(array_key_exists('Cache-Control', $response->headers), 
                '\'Cache-Control\' not found in the header.');
        $this->assertTrue(
                strrpos($response->headers['Cache-Control'], 'no-store') ===
                         false, 
                        'The \'no-store\' phrase not in header \'Cache-Control\'.');
    }

    /**
     * @test
     */
    public function checkCacheableView3 ()
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
        $request->view = array(
                'ctrl' => array(
                        'cacheable' => true,
                        'revalidate' => true,
                        'intermediate_cache' => false,
                        'max_age' => 100
                )
        );
        
        $response = $middleware->process_response($request, $response);
        $this->assertTrue(array_key_exists('Cache-Control', $response->headers), 
                '\'Cache-Control\' not found in the header.');
        $this->assertTrue(
                strrpos($response->headers['Cache-Control'], 'no-store') ===
                         false, 
                        'The \'no-store\' phrase exist in header \'Cache-Control\'.');
        $this->assertTrue(
                strrpos($response->headers['Cache-Control'], 'no-cache') !==
                         false, 
                        'The \'no-cache\' phrase not exist in header \'Cache-Control\'.');
        $this->assertTrue(
                strrpos($response->headers['Cache-Control'], 'private') !== false, 
                'The \'private\' phrase not exist in header \'Cache-Control\'.');
        $this->assertTrue(
                strrpos($response->headers['Cache-Control'], 'public') === false, 
                'The \'public\' phrase exist in header \'Cache-Control\'.');
    }

    /**
     * @test
     */
    public function checkCacheableView4 ()
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
        $request->view = array(
                'ctrl' => array(
                        'cacheable' => true,
                        'revalidate' => false,
                        'intermediate_cache' => false,
                        'max_age' => 100
                )
        );
        
        $response = $middleware->process_response($request, $response);
        $this->assertTrue(array_key_exists('Cache-Control', $response->headers), 
                '\'Cache-Control\' not found in the header.');
        $this->assertTrue(
                strrpos($response->headers['Cache-Control'], 'no-store') ===
                         false, 
                        'The \'no-store\' phrase exist in header \'Cache-Control\'.');
        $this->assertTrue(
                strrpos($response->headers['Cache-Control'], 'no-cache') ===
                         false, 
                        'The \'no-cache\' phrase exist in header \'Cache-Control\'.');
        $this->assertTrue(
                strrpos($response->headers['Cache-Control'], 'private') !== false, 
                'The \'private\' phrase not exist in header \'Cache-Control\'.');
        $this->assertTrue(
                strrpos($response->headers['Cache-Control'], 'public') === false, 
                'The \'public\' phrase exist in header \'Cache-Control\'.');
    }

    /**
     * @test
     */
    public function checkCacheableView5 ()
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
        $request->view = array(
                'ctrl' => array(
                        'cacheable' => true,
                        'revalidate' => false,
                        'intermediate_cache' => true,
                        'max_age' => 100
                )
        );
        
        $response = $middleware->process_response($request, $response);
        $this->assertTrue(array_key_exists('Cache-Control', $response->headers), 
                '\'Cache-Control\' not found in the header.');
        $this->assertTrue(
                strrpos($response->headers['Cache-Control'], 'no-store') ===
                         false, 
                        'The \'no-store\' phrase exist in header \'Cache-Control\'.');
        $this->assertTrue(
                strrpos($response->headers['Cache-Control'], 'no-cache') ===
                         false, 
                        'The \'no-cache\' phrase exist in header \'Cache-Control\'.');
        $this->assertTrue(
                strrpos($response->headers['Cache-Control'], 'private') === false, 
                'The \'private\' phrase exist in header \'Cache-Control\'.');
        $this->assertTrue(
                strrpos($response->headers['Cache-Control'], 'public') !== false, 
                'The \'public\' phrase exist in header \'Cache-Control\'.');
    }

    /**
     * @test
     */
    public function etagTest ()
    {
        $query = '/example/resource';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = 'http://localhost/example/resource';
        $_SERVER['REMOTE_ADDR'] = 'not set';
        $_SERVER['HTTP_HOST'] = 'localhost';
        $GLOBALS['_PX_uniqid'] = 'example';
        
        $middleware = new Cache_Middleware_RFC7234();
        $request = new Pluf_HTTP_Request($query);
        $response = new CacheTestHttpResponse('hi!');
        
        // empty view
        $request->view = array(
                'ctrl' => array(
                        'cacheable' => true,
                        'revalidate' => false,
                        'intermediate_cache' => true,
                        'max_age' => 100
                )
        );
        $request->HEADERS['If-None-Match'] = $response->etag();
        
        $response = $middleware->process_response($request, $response);
        $this->assertTrue(304 === $response->status_code, 
                'Status code is not 304');
    }
}
