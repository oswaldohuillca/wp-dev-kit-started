<?php

/**
 * Render image by ID
 * Require AFC plugin
 */
function render_image(int $id, array $attr = [])
{
  //If not exist $id return null
  if (!$id) return null;

  //If $id is array then extract $id['ID'] and find image by ID
  if (is_array($id)) {
    if (array_key_exists('ID', $id)) {
      return wp_get_attachment_image($id['ID'], "full", false, $attr);
    }
    return null;
  }

  return wp_get_attachment_image($id, "full", false, $attr);
}






/**
 * get image by ID
 * Require AFC plugin
 */
function get_image_url(int $id, string $default = ''): string
{
  return ($id) ? wp_get_attachment_image_url($id, "full", false) : $default;
}








/**
 * @param string {$value}
 * @param string {$default}
 * @return string
 */

function default_value($value, string $default = '')
{
  if (!$value || $value == null) return $default;
  return $value;
}








/**
 * @param array {$anchor}
 * @param array {$attr}
 * @param string {$default_label}
 * 
 */
function render_anchor($anchor = [], array $attr = [], string $default_label = '')
{
  if (is_array($anchor) || isset($anchor) || count($anchor) > 0) {

    $url = $anchor ? $anchor['url'] : '#';
    $label = default_value($anchor ? $anchor['title'] : '', $default_label);
    $target = $anchor ? $anchor['target'] : '_self';

    $attr = get_attr($attr);

    $anchor = "
      <a href='$url' target='$target' $attr>
        {$label}
      </a>
    ";
    return $anchor;
  }
  return "";
}








function get_attr(array $arr)
{
  $str = http_build_query($arr, '', ' ');
  $str = str_replace(['=', '&'], ['="', '" '], $str);
  $str = urldecode($str);
  return $str . '"';
}









/**
 * @todo Request Funcion similar to fetch in javascript
 */

function fetch(
  string $url,
  array $options = [
    'headers' => [],
    'method' => 'GET',
    'body' => []
  ]
) {
  if (!isset($url)) {
    throw new Exception("URL Not Found", 1);
    return;
  }

  $curl = curl_init();

  curl_setopt_array($curl, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => $options['method'],
    CURLOPT_POSTFIELDS => $options['body'],
    CURLOPT_HTTPHEADER => $options['headers'],
  ]);

  $response = curl_exec($curl);

  $err = curl_error($curl);

  if ($err) {
    throw new Exception('Curl err: ' . $err);
    return;
  }

  curl_close($curl);

  return $response;
}










function get_asset(string $path = '')
{
  return get_stylesheet_directory_uri() . '/public' . $path;
}











function get_data(string $key, array $data)
{
  if (array_key_exists($key, $data)) {
    return $data[$key];
  }
  return null;
}


// ICONS

function get_icon(string $name)
{
  return $GLOBALS['icons']->$name;
}


// BLOCK GUTENBERG

function get_name_title_block_file ($name){
  $name = str_replace(' ', '-', $name);
  return $name;
}

function get_title_block_name ($name){
  $name = str_replace('-', ' ', $name);
  return ucfirst($name);
}

function get_keywords_block_name ($name){
  return explode(" ", strtolower($name));
}

// CAL

function ca (int $number = 1){
  return "calc(100vw * ($number / var(--width-base)))";
}