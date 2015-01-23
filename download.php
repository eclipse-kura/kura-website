<?php

//=================
// Constants
//=================
// GA API URL
define("GA_URL", "http://google-analytics.com/collect", true);
// Eclipse stats URL
define("ECLIPSE_URL", "http://download.eclipse.org/stats/", true);
// S3 downloads bucket
define("S3_URL", "https://s3.amazonaws.com/kura_downloads/", true);
// URL parameters
define("TARGET",   $_GET['target'],   true);
define("VERSION",  $_GET['version'],  true);
define("RELEASE",  $_GET['release'],  true);
define("PLATFORM", $_GET['platform'], true);
// Random client ID
define("CLIENT_ID", rand(10, 900), true);

//=================
// Functions
//=================
//
// Send event to GA
function sendGA() {
  $data = array(
    'sc' => 'start',
    'v' => '1',
    'tid' => 'UA-56028459-1',
    'cid' => CLIENT_ID,
    't' => 'event',
    'ec' => 'downloads',
    'ea' => 'download',
    'el' => urlencode(TARGET)
  );

  foreach($data as $key=>$value) {
    $fields_string .= $key.'='.$value.'&';
    rtrim($field_string, '&');
  }

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, GA_URL);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, count($data));
  curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

  $result = curl_exec($ch);

  curl_close($ch);
}

//
// Send updated count to Eclipse
function sendEclipse() {
  $release = RELEASE === 'true' ? "release" : "snapshot";
  $url = ECLIPSE_URL . PLATFORM . "/" . $release . "/" . VERSION . "/" . TARGET;

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_HEADER, 1);
  curl_setopt($ch, CURLOPT_NOBODY, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

  $result = curl_exec($ch);

  curl_close($ch);
}

//
// Perform download redirect
function doDownload() {
  $release = RELEASE === 'true' ? "release" : "snapshot";
  $url = S3_URL . PLATFORM . "/" . $release . "/" . VERSION . "/" . TARGET;
  header('Location: ' . $url);
}

//=================
// Main
//=================
// Execute only if URL parameters are set
if (TARGET && VERSION && RELEASE && PLATFORM) {
  sendGA();
  sendEclipse();
  doDownload();
}
