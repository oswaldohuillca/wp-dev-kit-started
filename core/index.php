<?php

class WpDevKit
{
  protected $assets = [];

  public function __construct()
  {
    $this->get_script();
    $this->create_api_endpoint();
  }

  public static function load_env(): void
  {

    if (!file_exists(dirname(__DIR__) . "/.env")) return;

    $lines = file(dirname(__DIR__) . "/.env", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
      list($key, $value) = explode('=', $line);
      putenv("$key=$value");
    }
  }

  public function is_dev(): bool
  {
    if (boolval(getenv("DEVELOPMENT"))) return true;
    return false;
  }

  public function get_script(): void
  {

    if ($this->is_dev()) {
      $script_data_array = array(
        'url' => admin_url('admin-ajax.php'),
        'domain' => site_url("/"),
        'security' => wp_create_nonce('consult_ajax'),
      );

      $json_data = json_encode($script_data_array);

      // We need to add HMR ;)
      add_action("wp_footer", function () use ($json_data) {
        $script = <<<HTML
          <script>
            var wpCredentials = $json_data
          </script>
          <script type="module">
            import "http://localhost:5173/src/main.ts"
            window.process = {env:{NODE_ENV:"development"}}
          </script>
        HTML;
        echo $script;
      }, 30);
    } else {
      $this->assets = $this->get_assets();

      add_action('wp_enqueue_scripts', function () {
        $js = $this->assets["js"];
        wp_register_script('singulart-scripts', get_theme_file_uri() . "/dist/assets/{$js}", [], false, true);
        wp_enqueue_script('singulart-scripts');
      }, 30);

      add_action('wp_enqueue_scripts', function () {
        $css = $this->assets["css"];
        wp_enqueue_style('singulart-styles', get_theme_file_uri() . "/dist/assets/{$css}");
      }, 30);
    }
  }

  public function get_assets(): array
  {
    $js = "";
    $css = "";

    $folder = scandir(dirname(__DIR__) . "/dist/assets");

    foreach ($folder as $val) {
      if ($val) {
        if (preg_match('/.js/', $val)) {
          $js = $val;
        }
        if (preg_match('/.css/', $val)) {
          $css = $val;
        }
      }
    }

    return [
      "js" => $js,
      "css" => $css
    ];
  }


  /**
   * Asign API REST based in files
   * the folder base is "api"
   */

  private function get_API_folder(): string
  {
    return dirname(__DIR__) . '/api';
  }

  private function api_folder_exists(): bool
  {
    if (is_dir($this->get_API_folder())) return true;
    return false;
  }

  protected function read_api_files()
  {
    if (!$this->api_folder_exists()) return;
    $files = scandir($this->get_API_folder());

    // Delete ".", ".." first folders
    $files = array_slice($files, 2, count($files) - 2);

    return $files;
  }

  public function create_api_endpoint()
  {

    foreach ($this->read_api_files() as $file) {
      $file_dir  = $this->get_API_folder() . "/$file";
      if (is_file($file_dir) && file_exists($file_dir)) {

        // Validate if file extension is .php
        if (!preg_match('/\.php/', $file)) return;

        $filename = str_replace('.php', '', $file);

        include_once($file_dir);

        add_action("wp_ajax_{$filename}", "{$filename}_handler");
        add_action("wp_ajax_nopriv_{$filename}", "{$filename}_handler");
      }
    }
  }
}
