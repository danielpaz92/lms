<?php
function masterstudy_enqueue() {
	/*Course player scripts registration*/
	wp_register_script( 'masterstudy-course-player-assignments', STM_LMS_PRO_URL . 'assets/js/course-player/assignments.js', array( 'jquery' ), MS_LMS_VERSION, true );

	/*Course player styles registration*/
	wp_register_style( 'masterstudy-course-player-lesson-zoom', STM_LMS_PRO_URL . 'assets/css/course-player/zoom-conference.css', null, MS_LMS_VERSION );
	wp_register_style( 'masterstudy-course-player-lesson-stream', STM_LMS_PRO_URL . 'assets/css/course-player/stream.css', null, MS_LMS_VERSION );
	wp_register_style( 'masterstudy-course-player-lesson-google', STM_LMS_PRO_URL . 'assets/css/course-player/google-meet.css', null, MS_LMS_VERSION );
	wp_register_style( 'masterstudy-course-player-drip-content', STM_LMS_PRO_URL . '/assets/css/course-player/drip-content.css', null, MS_LMS_VERSION );
	wp_register_style( 'masterstudy-course-player-assignments', STM_LMS_PRO_URL . 'assets/css/course-player/assignments.css', null, MS_LMS_VERSION );

	/*Course player fonts styles registration*/
	wp_register_style( 'masterstudy-course-player-lesson-zoom-fonts', STM_LMS_PRO_URL . 'assets/css/course-player/fonts/zoom-conference.css', null, MS_LMS_VERSION );
	wp_register_style( 'masterstudy-course-player-lesson-stream-fonts', STM_LMS_PRO_URL . 'assets/css/course-player/fonts/stream.css', null, MS_LMS_VERSION );
	wp_register_style( 'masterstudy-course-player-lesson-google-fonts', STM_LMS_PRO_URL . 'assets/css/course-player/fonts/google-meet.css', null, MS_LMS_VERSION );
	wp_register_style( 'masterstudy-course-player-drip-content-fonts', STM_LMS_PRO_URL . '/assets/css/course-player/fonts/drip-content.css', null, MS_LMS_VERSION );
	wp_register_style( 'masterstudy-course-player-assignments-fonts', STM_LMS_PRO_URL . 'assets/css/course-player/fonts/assignments.css', null, MS_LMS_VERSION );

	/*Components scripts registration*/
	wp_register_script( 'masterstudy-buy-button-points', STM_LMS_PRO_URL . 'assets/js/components/buy-button/points.js', array( 'jquery' ), STM_LMS_PRO_VERSION, true );
	wp_register_script( 'masterstudy-buy-button-prerequisites', STM_LMS_PRO_URL . 'assets/js/components/buy-button/prerequisites.js', array( 'jquery' ), STM_LMS_PRO_VERSION, true );

	/*Components styles registration*/
	wp_register_style( 'masterstudy-buy-button-points', STM_LMS_PRO_URL . 'assets/css/components/buy-button/points.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-buy-button-group-courses', STM_LMS_PRO_URL . '/assets/css/components/buy-button/group-courses.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-buy-button-affiliate', STM_LMS_PRO_URL . '/assets/css/components/buy-button/affiliate.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-buy-button-prerequisites', STM_LMS_PRO_URL . 'assets/css/components/buy-button/prerequisite-button.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-prerequisites-info', STM_LMS_PRO_URL . 'assets/css/components/buy-button/prerequisite-info.css', null, STM_LMS_PRO_VERSION );

	/*Components fonts styles registration*/
	wp_register_style( 'masterstudy-buy-button-points-fonts', STM_LMS_PRO_URL . 'assets/css/components/fonts/buy-button/points.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-buy-button-group-courses-fonts', STM_LMS_PRO_URL . '/assets/css/components/fonts/buy-button/group-courses.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-buy-button-affiliate-fonts', STM_LMS_PRO_URL . '/assets/css/components/fonts/buy-button/affiliate.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-buy-button-prerequisites-fonts', STM_LMS_PRO_URL . 'assets/css/components/fonts/buy-button/prerequisite-button.css', null, STM_LMS_PRO_VERSION );
	wp_register_style( 'masterstudy-prerequisites-info-fonts', STM_LMS_PRO_URL . 'assets/css/components/fonts/buy-button/prerequisite-info.css', null, STM_LMS_PRO_VERSION );
}
add_action( 'wp_enqueue_scripts', 'masterstudy_enqueue' );
