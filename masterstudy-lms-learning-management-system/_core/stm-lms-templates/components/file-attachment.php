<?php

/**
 * @var array $attachments
 * @var boolean $dark_mode
 *
 * masterstudy-file-attachment_dark-mode - for dark mode
 */

wp_enqueue_style( 'masterstudy-file-attachment' );

$online_play_formats = array(
	'pdf',
	'video',
	'audio',
	'img',
);
if ( ! empty( $attachments ) ) {
	foreach ( $attachments as $attachment ) {
		$file = ms_plugin_attachment_data( $attachment );
		?>
		<div class="masterstudy-file-attachment <?php echo esc_attr( $dark_mode ? 'masterstudy-file-attachment_dark-mode' : '' ); ?>">
			<?php
			if ( 'img' === $file['current_format'] ) {
				$attachment_image = wp_get_attachment_image_src( $attachment->ID, 'thumbnail' );
				?>
				<img src="<?php echo esc_url( $attachment_image[0] ); ?>" class="masterstudy-file-attachment__image masterstudy-file-attachment__image_preview">
				<?php
			} else {
				?>
				<img src="<?php echo esc_url( STM_LMS_URL . "/assets/icons/files/new/{$file['current_format']}.svg" ); ?>" class="masterstudy-file-attachment__image">
			<?php } ?>
			<div class="masterstudy-file-attachment__wrapper">
				<?php if ( in_array( $file['current_format'], $online_play_formats, true ) ) { ?>
					<div class="masterstudy-file-attachment__title-wrapper">
						<a href="<?php echo esc_url( $file['url'] ); ?>" target="_blank" class="masterstudy-file-attachment__title">
							<?php echo esc_html( $file['file_title'] ); ?>
						</a>
					</div>
				<?php } else { ?>
					<span class="masterstudy-file-attachment__title">
						<?php echo esc_html( $file['file_title'] ); ?>
					</span>
				<?php } ?>
				<span class="masterstudy-file-attachment__size"><?php echo esc_html( $file['filesize'] . ' ' . $file['filesize_label'] ); ?></span>
				<a class="masterstudy-file-attachment__link" href="<?php echo esc_url( $file['url'] ); ?>" target="_blank" download>
					<?php echo esc_html__( 'Download', 'masterstudy-lms-learning-management-system' ); ?>
				</a>
			</div>
		</div>
		<?php
	}
}
