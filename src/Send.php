<?php
namespace Send;

/**
 * Perform a single HTTP request.
 *
 * The request
 * -----------
 * The $request array should contain all the data about your request. The only required field for the $request
 * parameter is 'url' (which should be a valid URL). 'method' must be one of 'GET', 'POST', 'PUT' and 'DELETE', and
 * is 'GET' by default. If you are sending a POST request, you should also populate the 'data' field with the POST
 * fields. For other request parameters, see the $curl_dictionary array defined at the beginning of this function
 *
 * The response
 * ------------
 * The server response is returned as a string. You should use json_decode or simplexml_load_string (if your
 * expected response is JSON or XML respectively) to convert it to an appropriate format.
 *
 * @param array       $request An array containing the request data.
 * @param null|String $error   If provided, this will be set to a string containing any errors.
 *
 * @return null|string String containing the response from the server, or NULL if the request is invalid.
 */
function send($request, &$error = null)
{
    $CURL_TRANSLATION = [
        'url'                => CURLOPT_URL,
        'data'               => CURLOPT_POSTFIELDS,
        'post'               => CURLOPT_POST,
        'get'                => CURLOPT_HTTPGET,
        'put'                => CURLOPT_PUT,
        'method'             => CURLOPT_CUSTOMREQUEST,
        'timeout'            => CURLOPT_TIMEOUT,
        'timeout_ms'         => CURLOPT_TIMEOUT_MS,
        'connect_timeout'    => CURLOPT_CONNECTTIMEOUT,
        'connect_timeout_ms' => CURLOPT_CONNECTTIMEOUT_MS,
        'headers'            => CURLOPT_HTTPHEADER,
        'user_agent'         => CURLOPT_USERAGENT,
        'file'               => CURLOPT_INFILE,
        'file_size'          => CURLOPT_INFILESIZE,
        'no_body'            => CURLOPT_NOBODY,
        'fetch_headers'      => CURLOPT_HEADER
    ];

    // Default method is GET
    if (!isset($request['method'])) {
        $request['method'] = 'GET';
    }

    // Validate the request
    $valid = array_key_exists('url', $request)
             && array_key_exists('method', $request)
             && in_array($request['method'], ['GET', 'POST', 'DELETE', 'PUT']);

    if (!$valid) {
        // Return nothing
        return null;
    }

    // Translate the request to cURL option constants
    $curl_options = [];

    foreach ($request as $option => $value) {
        // If the option is an int, assume that it's a CURLOPT
        if (is_int($option)) {
            $curl_options[$option] = $value;
        } // If the option is a string, assume it should be translated
        else {
            if (array_key_exists($option, $CURL_TRANSLATION)) {
                $curl_options[$CURL_TRANSLATION[$option]] = $value;
            }
        }
    }

    // Always ask for the data back
    $curl_options[CURLOPT_RETURNTRANSFER] = true;

    // Perform the request
    $curl = curl_init();

    // Error logging
    if (isset($error)) {
        curl_setopt($curl, CURLOPT_VERBOSE, true);
        $verbose = fopen('php://temp', 'w+');
        curl_setopt($curl, CURLOPT_STDERR, $verbose);
    }

    // Set parameters
    curl_setopt_array($curl, $curl_options);

    // Execute
    $str = curl_exec($curl);

    // Store error in the provided variable
    if (isset($error) && $str === false) {
        $error = "curl error (" . curl_errno($curl) . "): " . htmlspecialchars(curl_error($curl)) . "\n\n";
        rewind($verbose);
        $verboseLog = stream_get_contents($verbose);
        $error .= "Verbose information:\n" . htmlspecialchars($verboseLog) . "\n";
    }

    // Close the cURL handle
    curl_close($curl);

    // Return the result
    return $str;
}
