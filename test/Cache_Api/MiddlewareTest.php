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
 * Check cache middleware functionality
 * 
 * @author pluf.ir<info@pluf.ir>
 *
 */
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