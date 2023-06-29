<?php

namespace Oswaldo\WpDevKit\core;

class Api
{






  private function get_API_folder(): string
  {
    return dirname(dirname(__DIR__)) . '/api';
  }









  private function api_folder_exists(): bool
  {
    if (is_dir($this->get_API_folder())) return true;
    return false;
  }








  protected function read_api_files()
  {
    if (!$this->api_folder_exists()) return [];
    $files = scandir($this->get_API_folder());

    // Delete ".", ".." first folders
    $files = array_slice($files, 2, count($files) - 2);

    return $files;
  }










  public function create()
  {

    foreach ($this->read_api_files() as $file) {
      $file_dir  = $this->get_API_folder() . "/$file";
      if (is_file($file_dir) && file_exists($file_dir)) {

        // Validate if file extension is .php
        if (!preg_match('/\.php/', $file)) return;

        $filename = str_replace('.php', '', $file); // Extract filename

        include_once($file_dir); // Incuding API file

        $function_name = "{$filename}_handler"; // Function name

        if (function_exists($function_name)) { // Also if function exist proceced next step
          add_action("wp_ajax_{$filename}", $function_name);
          add_action("wp_ajax_nopriv_{$filename}", $function_name);
        }
      }
    }
  }
}
