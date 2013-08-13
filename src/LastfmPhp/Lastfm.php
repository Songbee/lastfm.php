<?php
namespace LastfmPhp;

use LastfmPhp\LastfmException;
use LastfmPhp\CURLException;

class Lastfm {
  private $key;
  private $secret;
  
  const API_ROOT = "http://ws.audioscrobbler.com/2.0/";

  public function __construct($key, $secret) {
    $this->key = $key;
    $this->secret = $secret;
  }

  public function read($method, $params) {
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_URL => self::API_ROOT . "?" . http_build_query(array_merge(
        $params,
        array(
          'method' => $method,
          'api_key' => $this->key,
          'format' => "json"
        )
      ))
    ));
    $ret = json_decode(curl_exec($curl));

    if (is_null($ret)) {
      throw new CURLException("CURL returned null", -1);
      return false;
    }

    if (isset($ret->error)) {
      throw new LastfmException($ret->message, $ret->key);
      return false;
    }

    return $ret;
  }
}