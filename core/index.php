<?php

class WpDevKit
{
  protected $assets = [];

  public function __construct()
  {
    $this->get_script();
    $this->get_reusable_functions();
    $this->create_api_endpoint();
  }

  public static function load_env(): void
  {

    if (!file_exists(dirname(__DIR__) . "/.env")) return;

    $lines = file(dirname(__DIR__) . "/.env", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
      if (preg_match('/=/', $line)) {
        list($key, $value) = explode('=', $line);
        if (isset($key) && isset($value)) {
          putenv("$key=$value");
        }
      }
    }
  }

  public function is_dev(): bool
  {
    if (boolval(getenv("DEVELOPMENT"))) return true;
    return false;
  }

  public function get_script(): void
  {

    $json_data = json_encode($this->get_wp_credentails());

    if ($this->is_dev()) {
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
      $js = $this->assets["js"];

      add_action('wp_enqueue_scripts', function () use ($json_data) {
        $js = $this->assets["js"];
        wp_register_script('wp-kit-scripts', get_theme_file_uri() . "/dist/assets/{$js}", [], false, true);

        wp_localize_script('wp-kit-scripts', 'wpCredentials', $this->get_wp_credentails());
        wp_enqueue_script('wp-kit-scripts');
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


  private function get_wp_credentails()
  {
    return array(
      'url' => admin_url('admin-ajax.php'),
      'public_url' => get_stylesheet_directory_uri() . '/public',
      'domain' => site_url("/"),
      'security' => wp_create_nonce('consult_ajax'),
    );
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


  /**
   * get core reusable functions
   */

  public function get_reusable_functions()
  {
    include __DIR__ . '/variables.php';
    include __DIR__ . '/blocks-gutenberg.php';
    include __DIR__ . '/utils.php';
  }
}
