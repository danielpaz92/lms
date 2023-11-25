<?php

/**
 * @var $title
 * @var $progress
 * @var $dark_mode
 *
 * masterstudy-progress_dark-mode - for dark mode
 */

wp_enqueue_style( 'masterstudy-progress' );
?>

<div class="masterstudy-progress <?php echo esc_attr( $dark_mode ? 'masterstudy-progress_dark-mode' : '' ); ?>">
	<div class="masterstudy-progress__bars">
		<span class="masterstudy-progress__bar-empty"></span>
		<span class="masterstudy-progress__bar-filled" style="width:<?php echo esc_html( $progress ); ?>%"></span>
	</div>
	<div class="masterstudy-progress__title">
		<?php echo esc_html( $title ) . ':'; ?>
		<?php echo esc_html( $progress ) . '%'; ?>
	</div>
</div>
