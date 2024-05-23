<?php
namespace App\Services;

class XmlrpcClient {

    private $url;

    public function __construct( $url ) {
        $this->url = $url;
    }

    /**
     * Call the XML-RPC method named $method and return the results, or die trying!
     *
     * @param string $method XML-RPC method name
     * @param mixed ... optional variable list of parameters to pass to XML-RPC call
     *
     * @return array result of XML-RPC call
     */
    public function call() {

        ini_set("display_error", 1);
        error_reporting(E_ALL);

        // get arguments
        $params = func_get_args();
        $method = array_shift( $params );

        $post = xmlrpc_encode_request( $method, $params );
        
        /*
            $post = str_replace("\n", "", $post);
            $post = str_replace(" ", "", $post);    
            echo $post;
        */
        
        $ch = curl_init();

        // set URL and other appropriate options
        curl_setopt( $ch, CURLOPT_URL,            $this->url );
        curl_setopt( $ch, CURLOPT_POST,           true );
        curl_setopt( $ch, CURLOPT_POSTFIELDS,     $post );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        // issue the request
        $response = curl_exec( $ch );
        $response_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
        $curl_errorno = curl_errno( $ch );
        $curl_error   = curl_error( $ch );
        curl_close( $ch );

        // check for curl errors
        if ( $curl_errorno != 0 ) {
            die( "Curl ERROR: {$curl_errorno} - {$curl_error}n" );
        }

        // check for server errors
        if ( $response_code != 200 ) {
            die( "ERROR: non-200 response from server: {$response_code} - {$response}n" );
        } 
        //    return $response;
        //    $response .= 'e>';
        return xmlrpc_decode( $response );
    }
}