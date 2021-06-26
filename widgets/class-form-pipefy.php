<?php
namespace ElementorPipefy\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Form_Pipefy extends Widget_Base {
	public static $slug = 'elementor-form-pipefy';

	/**
	 * Class constructor.
	 *
	 * @param array $data Widget data.
	 * @param array $args Widget arguments.
	 */
	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	public function get_name() { 
		return self::$slug; 
	}

	public function get_title() { 
		return __('Form Pipefy', self::$slug); 
	}

	public function get_icon() { 
		return 'fa fa-pencil'; 
	}

	public function get_categories() { 
		return [ 'basic' ]; 
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'elementor-pipefy' ),
			]
		);

		$this->add_control(
			'title',
			[
				'label'   => __( 'Title', 'elementor-pipefy' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Insert post ID', 'elementor-pipefy' ),
			]
		);

		$this->end_controls_section();
  	}

	protected function render(){
		$settings = $this->get_settings_for_display();

		$this->add_inline_editing_attributes( 'title', 'none' );
		?>
			<form action="./" class="form-pipefy" method="post" enctype="multipart/form-data">
				<h2>
					<?php echo $this->get_render_attribute_string( 'title' ); ?><?php echo wp_kses( $settings['title'], array() ); ?>
				</h2>
				<input type="text" name="postid" id="postid" placeholder="Post ID" aria-label="Campo de inserção ID Post">
				<button type="submit" id="efp-btn-submit"><?php _e('Submit'); ?></button>
				<div id="efp-messages"></div>
			</form>
		<?php
	}

	protected function _content_template(){
		?>
			<#
			view.addInlineEditingAttributes( 'title', 'none' );
			#>
			<form action="./" class="form-pipefy" method="post" enctype="multipart/form-data">
				<h2 {{{ view.getRenderAttributeString( 'title' ) }}}>
					{{{ settings.title }}}
				</h2>
				<input type="text" name="postid" id="postid" placeholder="Post ID" aria-label="Campo de inserção ID Post">
				<button type="submit"><?php _e('Submit'); ?></button>
			</form>
		<?php
	}
} 