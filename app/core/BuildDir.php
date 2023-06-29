<?php

namespace Oswaldo\WpDevKit\core;

// use Spatie\Ignition\Ignition;

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

      //Init Beatiful error view
      \Spatie\Ignition\Ignition::make()
        ->applicationPath(get_theme_file_path())
        ->register();


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

    // Read assets directory
    $assets = scandir(get_theme_file_path("/dist/assets"));

    // Delete . and ..
    $assets = array_slice($assets, 2, count($assets) - 2);

    foreach ($assets as $val) {
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
   * Get template directory
   */
  private function get_folder_template(): string
  {
    (string) $template_dir  = get_theme_file_path('templates');

    // Validate directory $template_dir exists
    if (!is_dir($template_dir)) {

      // Create directory $template_dir
      mkdir($template_dir);
    }

    return $template_dir;
  }








  /**
   * Read Files in template directory
   */

  private function read_templates(): array
  {
    $template_dir = $this->get_folder_template();

    // Read files in $template_folder
    $files = scandir($template_dir, SCANDIR_SORT_ASCENDING);

    // Delete . and ..
    $files = array_slice($files, 2, count($files) - 2);

    return $files;
  }
}
