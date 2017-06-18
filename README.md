Send provides a single function, send(), which performs one HTTP request.

## Usage

    use function Send\send;

Provide `send()` with an array defining your request to perform that request.

Get stuff:

    // Get the webpage for example.org
    
    $response = send([
        'url' => 'http://example.org',
    ]);
    
Post stuff:

    // Get the webpage for example.org
    
    $response = send([
        'url' => 'http://example.org',
        'method' => 'POST',
        'data' => $data
    ]);

You can of course also put stuff and delete stuff.

## Defining the request

`send()` takes one argument, which contains the data about your request. The only required field for the this argument
is 'url' (which should be a valid URL). 'method' must be one of 'GET', 'POST', 'PUT' and 'DELETE', and is
'GET' by default. If you are sending a POST request, you should also populate the 'data' field with the POST fields.

If you want to, you can use the options accepted by [curl_setopt](https://secure.php.net/manual/en/function.curl-setopt.php).
For convenience, the array keys can also be one of these "translations", and it will have the same effect:

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

## Optional error handling

If you want, you can provide a second argument which will contain a string with the error message returned by cURL:

    $error = "";
    $response = send($request, $error);
    if ($error) {
        echo ($error);
    }
