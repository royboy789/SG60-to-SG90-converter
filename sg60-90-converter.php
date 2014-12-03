<?php
/**
* Plugin Name: SG-60 to SG-90 Converter
* Plugin URI: http://arcctrl.com/plugins/sg-90
* Description: Convert your SG-60 plugins to SG-90
* Version: 0.1
* Author: ARC(CTRL)
* Author URI: http://www.arcctrl.com
* License: GPL2
*/

require 'sg_converter_class.php';
 
class sgConverter {
	
	function __construct() {
		add_action( 'admin_menu', array( $this, 'menuInit' ) );
		add_action( 'admin_init', array( $this, 'sg_converter_action' ) );
		
	}
	 
	function menuInit() {
		add_submenu_page( 'style-guide-main', 'SG Converter', 'SG Converter', 'delete_pages', 'sg-converter', array( $this, 'sg_converter_menu' ) );
	}

	function sg_converter_menu(){
		$args = array( 'post_type' => 'style-guides' );
		$loop = new WP_Query( $args );
		echo '<h1>Style Guide Converter</h1>';
		echo '<p>Convert all style guides created with SG-60 Style Guide Creator to work with the SG-90 Style Guide Creator</p>';
		echo '<h2>All Style Guides</h2>';
		echo '<div class="wrap feature-filter">';
			echo '<table class="widefat">';
				echo '<thead>';
					echo '<th>Style Guide Title</th>';
					echo '<th>Made With</th>';
					echo '<th>Convert</th>';
				echo '</thead>';
				echo '<tbody>';
					if( $loop->have_posts() ) : while( $loop->have_posts() ) : $loop->the_post();
						$ver = $this->versionChecker( get_the_ID() );
						echo '<tr>';
							echo '<td>'.get_the_title().'</td>';
							echo '<td>';
							if( $ver == 'sg-60' ) {
								echo 'SG-60 Style Guide Creator';
							} else {
								echo 'SG-90 Style Guide Creator';
							}
							echo '</td>';
							echo '<td>';
							if( $ver == 'sg-60' ) {
								echo '<form action="'.admin_url('admin.php?page=sg-converter').'" method="post">';
									echo '<input type="hidden" name="_sg_convert_id" value="'.get_the_ID().'" />';
									echo '<button class="button button-primary" value="Convert" name="_sg_convert">Convert</button>';
								echo '</form>';
							}
							echo '</td>';
						echo '</tr>';
					endwhile;
					else:
						echo '<tr><td colspan="3" align="center">No Style Guides Created <br/><br/>';
							echo '<a class="button button-primary" href="'.admin_url('post-new.php?post_type=style-guides').'">Create SG-90 Style Guide</a>';
						echo '</td>';
					endif;
				echo '</tbody>';
			echo '</table>';
		echo '</div>';
	}
	
	function versionChecker( $post_id ) {
		
		$ver = 'sg-90';
		if( get_post_meta( $post_id, '_logo_main', true ) || get_post_meta( $post_id, '_logos', true ) || get_post_meta( $post_id, '_colors', true ) || get_post_meta( $post_id, '_fonts', true ) || get_post_meta( $post_id, '_influences', true ) ){
			$ver = 'sg-60';
		}
		
		return $ver;
	}
	
	function sg_converter_action() {
		if( $_POST && isset( $_POST['_sg_convert_id'] ) ) {
			$converter = new _SG_CONVERTER();
			$converterResp = $converter->converter( intval( $_POST['_sg_convert_id'] ) );
			if( $converterResp === 'Not Able to Convert' ) {
				add_action( 'admin_notices', function(){
					?>
					<div class="error">
						<p><?php _e( 'Not Able to Convert', 'my-text-domain' ); ?></p>
					</div>
					<?php	
				});
			} else {
				add_action( 'admin_notices', function(){
					?>
					<div class="updated">
						<p><?php _e( 'Successfully Converted Style Guide', 'my-text-domain' ); ?></p>
					</div>
					<?php	
				});
			}
		}
	}
 }
 new sgConverter();
 ?>