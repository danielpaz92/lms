<?php

/**
 * @var int $delay
 * @var boolean $dark_mode
 *
 * masterstudy-loader_dark-mode - for dark mode
 */

wp_enqueue_script( 'masterstudy-loader' );
wp_localize_script(
	'masterstudy-loader',
	'data',
	array(
		'delay' => intval( $delay ),
	)
);
?>
<span class="masterstudy-loader <?php echo esc_attr( $dark_mode ? 'masterstudy-loader_dark-mode' : '' ); ?>">
	<img src="<?php echo esc_url( STM_LMS_URL . '/assets/icons/global/loader.svg' ); ?>">
</span>
