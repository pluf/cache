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
        $view = $request->view;
        $cacheable = array_key_exists('cacheable', $view) ? $view->cacheable : false;
        $revalidate = array_key_exists('revalidate', $view) ? $view->revalidate : false;
        $intermediate_cache = array_key_exists('revalidate', $view) ? $view->intermediate_cache : false;
        // default max_age: 7 day
        $max_age = array_key_exists('max_age', $view) ? $view->max_age : 604800;
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
}
