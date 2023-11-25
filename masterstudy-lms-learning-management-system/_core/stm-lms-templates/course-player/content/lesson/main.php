<?php

/**
 * @var int $post_id
 * @var int $item_id
 * @var string $lesson_type
 * @var boolean $dark_mode
 */

wp_enqueue_style( 'video.js' );
wp_enqueue_style( 'masterstudy-course-player-lesson' );
wp_enqueue_script( 'masterstudy-course-player-lesson' );

if ( function_exists( 'vc_asset_url' ) ) {
	wp_enqueue_style( 'stm_lms_wpb_front_css' );
}

if ( class_exists( 'Ultimate_VC_Addons' ) ) {
	STM_LMS_Lesson::aio_front_scripts();
}

$lesson = apply_filters( 'masterstudy_lms_course_player_lesson_query', $item_id );

if ( $lesson->have_posts() ) {
	?>
	<div class="masterstudy-course-player-lesson">
		<?php
		if ( 'video' === $lesson_type ) {
			STM_LMS_Templates::show_lms_template( 'course-player/content/lesson/video', array( 'id' => $item_id ) );
		}
		while ( $lesson->have_posts() ) {
			$lesson->the_post();
			ob_start();
			the_content();
			$content = ob_get_clean();
			$content = str_replace( '../../', site_url() . '/', $content );
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo stm_lms_filtered_output( $content );
		}
		?>
	</div>
	<span class="masterstudy-course-player-lesson__submit-trigger"></span>
	<?php
	wp_reset_postdata();
}
