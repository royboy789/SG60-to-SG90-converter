<?php

class _SG_CONVERTER {
	
	
	public function converter( $post_id ) {
		if( get_post_meta( $post_id, '_logo_main', true ) || get_post_meta( $post_id, '_logos', true ) || get_post_meta( $post_id, '_colors', true ) || get_post_meta( $post_id, '_fonts', true ) || get_post_meta( $post_id, '_influences', true ) ){
			
			$return = array();
			
			if( get_post_meta( $post_id, '_logo_main', true ) ) {
				$this->_logo_main_convert( $post_id );
				array_push( $return, 'logo main' );
			}
			if( get_post_meta( $post_id, '_logos', true ) ) {
				$this->_logos_convert( $post_id );
				array_push( $return, 'logos' );
			}
			if( get_post_meta( $post_id, '_colors', true ) ) {
				$this->_colors_convert( $post_id );
				array_push( $return, 'colors' );
			}
			if( get_post_meta( $post_id, '_fonts', true ) ) {
				$this->_fonts_convert( $post_id );
				array_push( $return, 'fonts' );
			}
			if( get_post_meta( $post_id, '_influences', true ) ) {
				$this->_influences_convert( $post_id );
				array_push( $return, 'influences' );
			}
			
			return $return;
			
		} else {
			return 'Not Able to Convert';
		}
	}
	
	private function _logo_main_convert( $post_id ) {
		$logo = array( get_post_meta( $post_id, '_logo_main', true ) );
		$this->addSection( 'Logo_Main', '_new-sg-Image_Box', $post_id );
		update_post_meta( $post_id, '_sg_Logo_Main_media_image_layout', 'layout_showcase' );
		update_post_meta( $post_id, '_sg_Logo_Main_media_image_modal', 'on' );
		update_post_meta( $post_id, '_sg_Logo_Main_media_image', $logo );
		
		delete_post_meta( $post_id, '_logo_main' );
		
	}
	
	private function _logos_convert( $post_id ) {
		$logos = get_post_meta( $post_id, '_logos', true );
		$this->addSection( 'Logos', '_new-sg-Image_Box', $post_id );
		update_post_meta( $post_id, '_sg_Logos_media_image_layout', 'layout_showcase' );
		update_post_meta( $post_id, '_sg_Logos_media_image_modal', 'on' );
		
		$newLogos = array();
		foreach( $logos as $logo ) {
			array_push( $newLogos, $logo );
		}
		
		update_post_meta( $post_id, '_sg_Logos_media_image', $newLogos );
		delete_post_meta( $post_id, '_logos' );
	}
	
	private function _colors_convert( $post_id ) {
		$newColors = array();
		$colors = get_post_meta( $post_id, '_colors', true );
		$this->addSection( 'Colors', '_new-sg-Color_Box', $post_id );
		
		
		$totalC = count( $colors );
		$i = 0;
		foreach( $colors as $color ) {
			$newColors['colorTitle'][] = $color['colorTitle'];
			$newColors['colorHex'][] = $color['colorHex'];
			$newColors['colorC'][] = $color['colorCMYK']['c'];
			$newColors['colorM'][] = $color['colorCMYK']['m'];
			$newColors['colorY'][] = $color['colorCMYK']['y'];
			$newColors['colorK'][] = $color['colorCMYK']['k'];
			$newColors['colorR'][] = $color['colorRGB']['r'];
			$newColors['colorG'][] = $color['colorRGB']['g'];
			$newColors['colorB'][] = $color['colorRGB']['b'];
			$i++;
		}
		
		update_post_meta( $post_id, '_sg_Colors_', $newColors );
		delete_post_meta( $post_id, '_colors' );
	}
	
	private function _fonts_convert( $post_id ) {
		$newFonts = array();
		$fonts = get_post_meta( $post_id, '_fonts', true );
		$this->addSection( 'Fonts', '_new-sg-Google_Fonts', $post_id );
		
		foreach( $fonts as $font ) {
			$newFonts['font'][] = $font['value'];
			$newFonts['tag'][] = 'h1';
			$newFonts['variant'][] = $font['weight'];
		}
		
		update_post_meta( $post_id, '_sg_Fonts_gFont', $newFonts );
		delete_post_meta( $post_id, '_fonts' );
		
	}
	
	private function _influences_convert( $post_id ) {
		$logos = get_post_meta( $post_id, '_influences', true );
		$this->addSection( 'Influences', '_new-sg-Image_Box', $post_id );
		update_post_meta( $post_id, '_sg_Influences_media_image_layout', 'layout_showcase' );
		update_post_meta( $post_id, '_sg_Influences_media_image_modal', 'on' );
		update_post_meta( $post_id, '_sg_Influences_media_image', $logos );
		
		delete_post_meta( $post_id, '_influences' );
	}
	
	private function addSection( $title, $className, $post_id ) {
		if( get_post_meta( $post_id, '_sg_sections', false ) ){
			
			$sections = get_post_meta( $post_id, '_sg_sections', false );
			array_push( $sections[0], array( 'title' => $title, 'class' => $className ) );
			update_post_meta( $post_id, '_sg_sections', $sections[0] );
			
		} else {
			
			$sections = array( array( 'title' => $title, 'class' => $className ) );
			add_post_meta( $post_id, '_sg_sections', $sections );
			
		}
		
	}
	
}	
	
?>