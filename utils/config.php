<?php

class WpDevKit
{

  public $assets = [];

  public function __construct()
  {
    $this->get_script();
  }

  public static function load_env(): void
  {
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

      // We need to add HMR ;)
      $script = <<<HTML
            <script type="module">
            import "http://localhost:5173/src/main.ts"
            window.process = { env: { NODE_ENV: "development" }}
            </script>
        HTML;

      echo $script;
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
}
