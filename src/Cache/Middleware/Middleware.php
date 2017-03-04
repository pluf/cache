<?php

/**
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
     * Adds some directives to header of resonse to cache response in client side.
     * This method is base on information from below url:
     * @see https://developers.google.com/web/fundamentals/performance/optimizing-content-efficiency/http-caching
     * 
     * @param Pluf_HTTP_Request $request 
     *  The request
     * @param Pluf_HTTP_Response $resonse 
     *  The response
     * @return Pluf_HTTP_Response The response
     */
    function process_response ($request, $response)
    {
        $view = $request->view;
        $cacheable = false;
        $revalidate = false;
        $intermediate_cache = false;
        $max_age = 604800; // 7 day
        $etag = null;
        if(array_key_exists('cacheable', $view)){
            
        }
        
        $prefix = '/api/';
        if (strncmp($request->query, $prefix, strlen($prefix)) === 0){
            $response->headers['Cache-Control'] = 'no-cache, no-store, must-revalidate';
            $response->headers['Pragma'] = 'no-cache';
            $response->headers['Expires'] = '0';
        }
        return $response;
    }
}
