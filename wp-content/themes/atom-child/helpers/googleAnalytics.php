<?php

//Send Data to Google Analytics
//https://developers.google.com/analytics/devguides/collection/protocol/v1/devguide#event
function ga_send_data($data)
{
    $getString = 'https://ssl.google-analytics.com/collect';
    $getString .= '?payload_data&';
    $getString .= http_build_query($data);
    $result = wp_remote_get($getString);
    return $result;
}

//Send Event Function for Server-Side Google Analytics
function ga_send_event($path = null, $title = null)
{
    //GA Parameter Guide: https://developers.google.com/analytics/devguides/collection/protocol/v1/parameters?hl=en
    //GA Hit Builder: https://ga-dev-tools.appspot.com/hit-builder/
    $data = array(
        'v' => 1,
        'tid' => 'UA-5740821-1',
        'cid' => ga_parse_cookie(),
        't' => 'pageview',
        'dh' => '', //Document hostname
        'dp' => $path, //Document path
        'dt' => $title, //Document title
        'ua' => rawurlencode($_SERVER['HTTP_USER_AGENT'])
    );
    $result = ga_send_data($data);
}

//Generate UUID v4 function (needed to generate a CID when one isn't available)
function ga_generate_UUID()
{
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff), //32 bits for "time_low"
        mt_rand(0, 0xffff), //16 bits for "time_mid"
        mt_rand(0, 0x0fff) | 0x4000, //16 bits for "time_hi_and_version", Four most significant bits holds version number 4
        mt_rand(0, 0x3fff) | 0x8000, //16 bits, 8 bits for "clk_seq_hi_res", 8 bits for "clk_seq_low", Two most significant bits holds zero and one for variant DCE1.1
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff) //48 bits for "node"
    );
}

//Handle the parsing of the _ga cookie or setting it to a unique identifier
function ga_parse_cookie()
{
    if (isset($_COOKIE['_ga'])) {
        list($version, $domainDepth, $cid1, $cid2) = explode('.', $_COOKIE["_ga"], 4);
        $contents = array('version' => $version, 'domainDepth' => $domainDepth, 'cid' => $cid1 . '.' . $cid2);
        $cid = $contents['cid'];
    } else {
        $cid = ga_generate_UUID();
    }
    return $cid;
}
