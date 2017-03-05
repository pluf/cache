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

/**
 * Cache middleware
 * 
 * Add necessary directives to header of response to cache response in client side.
 * Added directives to header of response in this middleware are based on RFC-7234.
 *
 *
 * @author maso<mostafa.barmshory@dpq.co.ir>
 * @author hadi<mohammad.hadi.mansouri@dpq.co.ir>
 */
class Cache_Middleware_RFC7234
{

    /**
     * Adds some directives to header of resonse to cache response in client
     * side.
     * This method is base on information from below url:
     *
     * @see https://developers.google.com/web/fundamentals/performance/optimizing-content-efficiency/http-caching
     *
     * @param Pluf_HTTP_Request $request
     *            The request
     * @param Pluf_HTTP_Response $resonse
     *            The response
     * @return Pluf_HTTP_Response The response
     */
    function process_response ($request, $response)
    {
        // Request with If-None-Match header
        if($request->method === 'GET' && array_key_exists('If-None-Match', $request->HEADERS)){
            $matches = $request->HEADERS['If-None-Match'];
            if(!is_array($matches)){
                $stamp = [$matches];
            }
            foreach($matches as $stamp){                
                if(strcmp($stamp, $response->headers['ETag']) === 0){
                    // 304 (Not Modified) response
                    $res = Pluf_HTTP_Response_NotModified();
                    $res->headers = $response->headers;
                    return $res;
                }
            }
        }
        
        $view = $request->view;
        $cacheable = array_key_exists('cacheable', $view['ctrl']) ? $view['ctrl']['cacheable'] : false;
        $revalidate = array_key_exists('revalidate', $view['ctrl']) ? $view['ctrl']['revalidate'] : false;
        $intermediate_cache = array_key_exists('intermediate_cache', $view['ctrl']) ? $view['ctrl']['intermediate_cache'] : true;
        $max_age = array_key_exists('max_age', $view['ctrl']) ? $view['ctrl']['max_age'] : 604800;
        $etag = method_exists($response, 'etag') ? $response->etag() : null;
        
        //TODO: hadi, 1395: check if values in Cache-Controll should be separated by , or ;
        
        if (! $cacheable) {
            $response->headers['Cache-Control'] = array_key_exists(
                    'Cache-Control', $response->headers) ? $response->headers['Cache-Control'] .
                     ', no-store' : 'no-store';
            return $response;
        }
        if($revalidate) {
            $response->headers['Cache-Control'] = array_key_exists(
                    'Cache-Control', $response->headers) ? $response->headers['Cache-Control'] .
                     ', no-cache' : 'no-cache';
        }
        if($intermediate_cache){
            $response->headers['Cache-Control'] = array_key_exists(
                    'Cache-Control', $response->headers) ? $response->headers['Cache-Control'] .
                    ', public' : 'public';
        }else{
            $response->headers['Cache-Control'] = array_key_exists(
                    'Cache-Control', $response->headers) ? $response->headers['Cache-Control'] .
                    ', private' : 'private';
        }
        $response->headers['Cache-Control'] = $response->headers['Cache-Control'] . ', max_age=' . $max_age;
        if($etag !== null)
            $response->headers['ETag'] = '"'.$etag.'"';
        
        return $response;
    }
    
    function process_request($request){
        return false;
    }
}