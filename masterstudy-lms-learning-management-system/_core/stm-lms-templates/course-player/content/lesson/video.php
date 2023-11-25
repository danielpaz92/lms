<?php

/**
 * @var int $id
 */

wp_enqueue_style( 'masterstudy-course-player-lesson-video' );
wp_enqueue_script( 'masterstudy-course-player-lesson-video' );

$data = apply_filters( 'masterstudy_lms_course_player_lesson_video', $id );
?>

<div class="masterstudy-course-player-lesson-video">
	<?php
	if ( 'embed' === $data['video_type'] && ! empty( $data['embed_ctx'] ) ) {
		?>
		<div class="masterstudy-course-player-lesson-video__embed-wrapper">
			<?php echo wp_kses( htmlspecialchars_decode( $data['embed_ctx'] ), stm_lms_allowed_html() ); ?>
		</div>
		<?php
	} elseif ( in_array( $data['video_type'], array( 'html', 'ext_link' ), true ) && ! empty( $data['uploaded_video'] ) ) {
		if ( strpos( $data['uploaded_video'], 'embed' ) ) {
			?>
			<embed src="<?php echo esc_url( $data['uploaded_video'] ); ?>">
			<?php
		} else {
			?>
			<div class="masterstudy-course-player-lesson-video__wrapper">
				<span class="masterstudy-course-player-lesson-video__play-button"></span>
				<video id="masterstudy-course-player-lesson-video" data-id="<?php echo esc_attr( $id ); ?>" poster="<?php echo esc_url( $data['poster'] ); ?>" controls controlsList="nodownload" style="<?php echo esc_attr( 'html' === $data['video_type'] && ! empty( $data['video_width'] ) ? 'max-width: ' . $data['video_width'] . 'px' : '' ); ?>">
					<source src="<?php echo esc_url( $data['uploaded_video'] ); ?>" type='video/<?php echo esc_attr( $data['video_format'] ); ?>'>
				</video>
			</div>
			<?php
		}
	} elseif ( in_array( $data['video_type'], array( 'youtube', 'vimeo' ), true ) ) {
		?>
		<iframe src="<?php echo esc_attr( 'youtube' === $data['video_type'] ? $data['youtube'] : $data['vimeo'] ); ?>" frameborder="0" allowfullscreen allowtransparency allow="autoplay"></iframe>
		<?php
	} elseif ( in_array( $data['video_type'], array( 'presto_player', 'shortcode' ), true ) ) {
		echo 'presto_player' === $data['video_type'] && ! empty( $data['presto_player_idx'] ) ? do_shortcode( '[presto_player id="' . esc_attr( $data['presto_player_idx'] ) . '"]' ) : do_shortcode( $data['shortcode'] );
	}
	?>
</div>
