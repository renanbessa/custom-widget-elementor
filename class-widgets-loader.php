<?php 

namespace ElementorPipefy\Widgets;

class Widgets_Loader{

  private static $_instance = null;

  public static function instance()
  {
    if (is_null(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  private function include_widgets_files(){
    require_once(__DIR__ . '/widgets/class-form-pipefy.php');
  }

  public function register_widgets(){
    $this->include_widgets_files();

    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Form_Pipefy());
  }

  public function __construct(){
    add_action('elementor/widgets/widgets_registered', [$this, 'register_widgets'], 99);
    add_action('wp_enqueue_scripts', [ $this, 'efp_load_assets' ] );
    
    add_action('wp_ajax_efp_send_data_post', [ $this, 'efp_send_data_post']);
		add_action('wp_ajax_nopriv_efp_send_data_post', [ $this, 'efp_send_data_post']);
  }

  public function efp_send_data_post(){
    check_ajax_referer('efp_send_data_post', 'security');

    require_once(__DIR__ . '/widgets/class-post-validator.php');
    $validation = new Post_Validator($_POST); 
    $messages = [];
    $messages[] = $validation->validateForm();
    $ok = $validation->isSuccess();

		echo json_encode([
			'ok' => $ok,
			'messages' => $messages
		]);

		wp_die();
	}

  public function efp_load_assets() {
    wp_enqueue_style( 'elementor-pipefy-css', plugins_url( '/assets/css/elementor-pipefy.css', ELEMENTOR_PIPEFY ), [], '' );
    wp_enqueue_script( 'elementor-pipefy-js', plugins_url( '/assets/js/elementor-pipefy.js', ELEMENTOR_PIPEFY ), [], null, true );

    wp_localize_script( 'elementor-pipefy-js', 'efpsettings', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
		    'security' => wp_create_nonce('efp_send_data_post'),
		    'action' => 'efp_send_data_post',
    ) );
	}
}

// Instantiate Plugin Class
Widgets_Loader::instance();