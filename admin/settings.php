<?php

add_action( 'admin_menu', 'fwe_add_admin_menu' );
add_action( 'admin_init', 'fwe_settings_init' );


function fwe_add_admin_menu() {
	add_options_page( 'Fast WordPress Embeds', 'Fast WordPress Embeds', 'manage_options', 'fast_wordpress_embeds', 'fast_wordpress_embeds_options_page' );
}


function fwe_settings_init() {
	register_setting( 'pluginPage', 'fwe_settings' );

	// default values
	$fwe_defaults = array(
		'fwe_default_width' => '300px',
		'fwe_enable_youtube' => 1,
		'fwe_enable_vimeo' => 1,
		'fwe_youtube_width' => '300px',
		'fwe_vimeo_width' => '300px'
	);
	if( ! get_option( 'fwe_settings' ) ) {
		update_option( 'fwe_settings', $fwe_defaults);
	}
	
	add_settings_section(
		'fwe_pluginPage_section', 
		__( 'Enable or disable embed providers', 'fwe' ), 
		'fwe_settings_section_callback', 
		'pluginPage'
	);

	$option_values = get_option( 'fwe_settings' );

	add_settings_field(
		'fwe_default_width', 
		__( 'Default width', 'fwe' ), 
		'fwe_default_width_render', 
		'pluginPage', 
		'fwe_pluginPage_section',
		array(
			'label_for' => 'default_width',
			'value' => isset( $option_values['fwe_default_width'] ) ? $option_values['fwe_default_width'] : "300px"
		)
	);

	add_settings_field(
		'fwe_enable_youtube', 
		__( 'YouTube', 'fwe' ), 
		'fwe_enable_youtube_render', 
		'pluginPage', 
		'fwe_pluginPage_section',
		array(
			'label_for' => 'fwe_enable_youtube',
			'value' => isset( $option_values['fwe_enable_youtube'] ) ? 1 : 0,
			'labelwidth' => "Default width for YouTube",
			'defaultwidth' => isset( $option_values['fwe_youtube_width'] ) ? $option_values['fwe_youtube_width'] : "300px"
		)
	);

	add_settings_field(
		'fwe_enable_vimeo', 
		__( 'Vimeo', 'fwe' ), 
		'fwe_enable_vimeo_render', 
		'pluginPage', 
		'fwe_pluginPage_section',
		array(
			'label_for' => 'fwe_enable_vimeo',
			'value' => isset( $option_values['fwe_enable_vimeo'] ) ? 1 : 0,
			'labelwidth' => "Default width for Vimeo",
			'defaultwidth' => isset( $option_values['fwe_vimeo_width'] ) ? $option_values['fwe_vimeo_width'] : "300px"
		)
	);

	// Support for wordpress.tv not complete
	// add_settings_field(
	// 	'fwe_enable_wordpresstv', 
	// 	__( 'wordpress.tv', 'fwe' ), 
	// 	'fwe_enable_wordpresstv_render', 
	// 	'pluginPage', 
	// 	'fwe_pluginPage_section',
	// 	array(
	// 		'label_for' => 'fwe_enable_wordpresstv',
	// 		'value' => isset( $option_values['fwe_enable_wordpresstv'] ) ? 1 : 0,
	//		'labelwidth' => "Default width for wordpress.tv",
	//		'defaultwidth' => isset( $option_values['fwe_wordpresstv_width'] ) ? $option_values['fwe_wordpresstv_width'] : "300px"
	// 	)
	// );

}


function fwe_default_width_render( $args ) {
	?>
	<input type="text" value="<?php echo $args['value'] ?>" name="fwe_settings[fwe_default_width]" id="<?php echo $args['label_for'] ?>">
	<?php	
}

function fwe_enable_youtube_render( $args ) {
	?>
	<input type="checkbox" value="1" <?php checked( $args['value'], 1 ) ?> name="fwe_settings[fwe_enable_youtube]" id="<?php echo $args['label_for'] ?>">
	<label for="youtubewidth"><?php echo $args['labelwidth'] ?></label>
	<input type="text" value="<?php echo $args['defaultwidth'] ?>" name="fwe_settings[fwe_youtube_width]" id="youtubewidth">
	<?php
}

function fwe_enable_vimeo_render( $args ) {
	?>
	<input type="checkbox" value="1" <?php checked( $args['value'], 1 ) ?> name="fwe_settings[fwe_enable_vimeo]" id="<?php echo $args['label_for'] ?>">
	<label for="vimeowidth"><?php echo $args['labelwidth'] ?></label>
	<input type="text" value="<?php echo $args['defaultwidth'] ?>" name="fwe_settings[fwe_vimeo_width]" id="vimeowidth">
	<?php
}

// Support for wordpress.tv not complete
function fwe_enable_wordpresstv_render( $args ) {
	?>
	<input type="checkbox" value="1" <?php checked( $args['value'], 1 ) ?> name="fwe_settings[fwe_enable_wordpresstv]" id="<?php echo $args['label_for'] ?>">
	<label for="wordpresstvwidth"><?php echo $args['labelwidth'] ?></label>
	<input type="text" value="<?php echo $args['defaultwidth'] ?>" name="fwe_settings[fwe_vimeo_width]" id="wordpresstvwidth">
	<?php
}


function fwe_settings_section_callback() {
	echo __( 'Check the embeds you want loaded fast!', 'fwe' );
}


function fast_wordpress_embeds_options_page() {
	?>
	<form action='options.php' method='post'>
		
		<h2>Fast WordPress Embeds Options</h2>
		
		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>
		
	</form>
	<?php
}
