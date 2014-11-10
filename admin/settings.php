<?php

add_action( 'admin_menu', 'fwe_add_admin_menu' );
add_action( 'admin_init', 'fwe_settings_init' );


function fwe_add_admin_menu() {
	add_options_page( 'Fast WordPress Embeds', 'Fast WordPress Embeds', 'manage_options', 'fast_wordpress_embeds', 'fast_wordpress_embeds_options_page' );
}

function fwe_settings_exist() {
	if( false == get_option( 'fast_wordpress_embeds_settings' ) ) {
		add_option( 'fast_wordpress_embeds_settings' );
	}
}


function fwe_settings_init() {
	register_setting( 'pluginPage', 'fwe_settings' );

	add_settings_section(
		'fwe_pluginPage_section', 
		__( 'Enable or disable embed providers', 'fwe' ), 
		'fwe_settings_section_callback', 
		'pluginPage'
	);

	$option_values = get_option( 'fwe_settings' );

	add_settings_field(
		'fwe_enable_youtube', 
		__( 'YouTube', 'fwe' ), 
		'fwe_enable_youtube_render', 
		'pluginPage', 
		'fwe_pluginPage_section',
		array(
			'label_for' => 'fwe_enable_youtube',
			'value' => isset( $option_values['fwe_enable_youtube'] ) ? 1 : 0
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
			'value' => isset( $option_values['fwe_enable_vimeo'] ) ? 1 : 0
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
	// 		'value' => isset( $option_values['fwe_enable_wordpresstv'] ) ? 1 : 0
	// 	)
	// );

}


function fwe_enable_youtube_render( $args ) {
	?>
	<input type="checkbox" value="1" <?php checked( $args['value'], 1 ) ?> name="fwe_settings[fwe_enable_youtube]" id="<?php echo $args['label_for'] ?>">
	<?php
}

function fwe_enable_vimeo_render( $args ) {
	?>
	<input type="checkbox" value="1" <?php checked( $args['value'], 1 ) ?> name="fwe_settings[fwe_enable_vimeo]" id="<?php echo $args['label_for'] ?>">
	<?php
}

// Support for wordpress.tv not complete
function fwe_enable_wordpresstv_render( $args ) {
	?>
	<input type="checkbox" value="1" <?php checked( $args['value'], 1 ) ?> name="fwe_settings[fwe_enable_wordpresstv]" id="<?php echo $args['label_for'] ?>">
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