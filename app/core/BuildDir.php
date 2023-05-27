<?php

namespace Oswaldohuillca\WpDevKitStarted\core;

class BuildDir
{

  protected $assets = [];

  public function get()
  {
    $this->get_script();
  }

  private function get_script(): void
  {

    $json_data = json_encode($this->get_wp_credentails());

    if (getenv('IS_DEV')) {
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

  private function get_assets(): array
  {
    $js = "";
    $css = "";

    $folder = scandir(dirname(dirname(__DIR__)) . "/dist/assets");

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
}
