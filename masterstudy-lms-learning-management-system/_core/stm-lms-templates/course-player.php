<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * @var int    $lesson_id
 * @var string $lms_page_path
 */

do_action( 'masterstudy_lms_course_player_register_scripts' );
do_action( 'masterstudy_lms_course_player_register_styles' );

wp_enqueue_style( 'masterstudy-course-player-main' );

$data = apply_filters( 'masterstudy_lms_course_player_main_template_data', $lesson_id, $lms_page_path );

if ( empty( $data['theme_fonts'] ) ) {
	wp_enqueue_style( 'masterstudy-course-player-fonts' );
	wp_enqueue_style( 'masterstudy-components-fonts' );
}

do_action( 'stm_lms_before_item_template_start', $data['post_id'], $data['item_id'] );

if ( ! empty( $data['custom_css'] ) ) {
	wp_add_inline_style( 'masterstudy-course-player-custom', $data['custom_css'] );
}

do_action( 'stm_lms_template_main' );

$attachments = apply_filters( 'masterstudy_lms_course_player_lesson_materials', json_decode( get_post_meta( $data['item_id'], 'lesson_files', true ) ) );
$quiz_data   = ( 'quiz' === $data['lesson_type'] ) ? apply_filters( 'masterstudy_lms_course_player_quiz_query', $data['item_id'], $data['post_id'] ) : array();

STM_LMS_Templates::show_lms_template(
	'course-player/header',
	array(
		'attachments'              => $attachments,
		'course_title'             => $data['course_title'],
		'lesson_type'              => $data['lesson_type'],
		'has_access'               => $data['has_access'],
		'has_preview'              => $data['has_preview'],
		'lesson_lock_before_start' => $data['lesson_lock_before_start'],
		'course_url'               => $data['course_url'],
		'user_page_url'            => $data['user_page_url'],
		'quiz_duration'            => ( 'quiz' === $data['content_type'] ) ? $quiz_data['duration'] : '',
		'is_scorm_course'          => $data['is_scorm_course'],
		'show_logo'                => $data['show_logo'],
		'logo_url'                 => $data['logo_url'],
		'dark_mode'                => $data['dark_mode'],
		'theme_fonts'              => $data['theme_fonts'],
		'discussions_sidebar'      => $data['discussions_sidebar'],
		'current_user'             => $data['user'],
	)
);

STM_LMS_Templates::show_lms_template(
	'components/loader',
	array(
		'delay'     => 1200,
		'dark_mode' => $data['dark_mode'],
	)
);

if ( apply_filters( 'stm_lms_stop_item_output', false, $data['post_id'] ) ) {
	if ( $data['has_access'] || $data['has_preview'] ) {
		do_action( 'stm_lms_before_item_lesson_start', $data['post_id'], $data['item_id'] );
	} else {
		?>
		<div class="masterstudy-course-player-content masterstudy-course-player-content_locked <?php echo esc_attr( $data['dark_mode'] ? 'masterstudy-course-player-content_dark-mode' : '' ); ?>">
			<div class="masterstudy-course-player-content__wrapper">
				<?php
				STM_LMS_Templates::show_lms_template(
					'course-player/locked',
					array(
						'post_id'   => $data['post_id'],
						'item_id'   => $data['item_id'],
						'dark_mode' => $data['dark_mode'],
					)
				);
				?>
			</div>
		</div>
		<?php
	}
} else {
	if ( $data['has_access'] && ! $data['has_trial_access'] ) {
		do_action( 'stm_lms_lesson_started', $data['post_id'], $data['item_id'], '' );
	}

	stm_lms_update_user_current_lesson( $data['post_id'], $data['item_id'] );

	do_action( 'masterstudy_lms_course_player_update_user_current_lesson', $data['post_id'], $data['item_id'] );
	?>
	<div class="masterstudy-course-player-content <?php echo esc_attr( $data['dark_mode'] ? 'masterstudy-course-player-content_dark-mode' : '' ); ?>">
		<?php
		if ( ! $data['is_scorm_course'] && ( $data['has_access'] || $data['has_preview'] ) ) {
			STM_LMS_Templates::show_lms_template(
				'course-player/curriculum',
				array(
					'post_id'       => $data['post_id'],
					'item_id'       => $data['item_id'],
					'course_title'  => $data['course_title'],
					'user'          => $data['user'],
					'curriculum'    => $data['curriculum'],
					'trial_lessons' => $data['trial_lesson_count'],
					'trial_access'  => $data['has_trial_access'],
					'is_enrolled'   => $data['is_enrolled'],
					'dark_mode'     => $data['dark_mode'],
				)
			);
		}
		?>
		<div class="masterstudy-course-player-content__wrapper">
			<?php
			if ( $data['has_access'] || $data['has_preview'] ) {
				if ( ! $data['lesson_lock_before_start'] && ! $data['lesson_locked_by_drip'] ) {
					?>
					<div class="masterstudy-course-player-content__header <?php echo esc_attr( 'quiz' === $data['lesson_type'] ? 'masterstudy-course-player-content__header_quiz' : '' ); ?>">
						<span class="masterstudy-course-player-content__header-lesson-type">
							<?php echo esc_html( $data['lesson_type_label'] ); ?>
						</span>
						<h1><?php echo esc_html( get_the_title( $data['item_id'] ) ); ?></h1>
					</div>
					<?php
				}

				$item_content = apply_filters( 'stm_lms_show_item_content', true, $data['post_id'], $data['item_id'] );

				if ( $item_content && ! empty( $data['item_id'] ) ) {
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo apply_filters(
						'stm_lms_lesson_content',
						STM_LMS_Templates::show_lms_template(
							'course-player/content/' . $data['content_type'] . '/main',
							array(
								'post_id'     => $data['post_id'],
								'item_id'     => $data['item_id'],
								'lesson_type' => $data['lesson_type'],
								'data'        => ( 'quiz' === $data['content_type'] ) ? $quiz_data : array(),
								'last_lesson' => $data['last_lesson'],
								'dark_mode'   => $data['dark_mode'],
							)
						),
						$data['post_id'],
						$data['item_id']
					);
				}

				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo apply_filters( 'stm_lms_course_item_content', $content = '', $data['post_id'], $data['item_id'] );

				if ( ! empty( $attachments ) && ! $data['lesson_lock_before_start'] ) {
					STM_LMS_Templates::show_lms_template(
						'course-player/content/lesson/materials',
						array(
							'attachments' => $attachments,
							'dark_mode'   => $data['dark_mode'],
						)
					);
				}

				if ( ! empty( $data['last_lesson'] ) && intval( $data['item_id'] ) === intval( $data['last_lesson'] ) ) {
					STM_LMS_Templates::show_lms_template(
						'course-player/course-completed',
						array(
							'post_id'   => $data['post_id'],
							'item_id'   => $data['item_id'],
							'dark_mode' => $data['dark_mode'],
						)
					);
				}
			} else {
				STM_LMS_Templates::show_lms_template(
					'course-player/locked',
					array(
						'post_id'   => $data['post_id'],
						'item_id'   => $data['item_id'],
						'dark_mode' => $data['dark_mode'],
					)
				);
			}

			if ( ! empty( $data['current_material'] ) ) {
				STM_LMS_Templates::show_lms_template(
					'course-player/navigation',
					array(
						'post_id'                  => $data['post_id'],
						'item_id'                  => $data['item_id'],
						'lesson_type'              => $data['lesson_type'],
						'material_ids'             => $data['material_ids'],
						'current_material'         => $data['current_material'],
						'has_access'               => $data['has_access'],
						'lesson_lock_before_start' => $data['lesson_lock_before_start'],
						'lesson_locked_by_drip'    => $data['lesson_locked_by_drip'],
						'dark_mode'                => $data['dark_mode'],
						'current_user'             => $data['user'],
					)
				);
			}
			?>
		</div>
		<?php
		if ( $data['has_access'] ) {
			STM_LMS_Templates::show_lms_template(
				'course-player/discussions',
				array(
					'post_id'             => $data['post_id'],
					'item_id'             => $data['item_id'],
					'lesson_type'         => $data['lesson_type'],
					'quiz_data'           => ( 'quiz' === $data['content_type'] ) ? $quiz_data : array(),
					'dark_mode'           => $data['dark_mode'],
					'discussions_sidebar' => $data['discussions_sidebar'],
					'current_user'        => $data['user'],
				)
			);
		}
		?>
	</div>
	<?php
}

do_action( 'template_redirect' );

STM_LMS_Templates::show_lms_template( 'course-player/footer', array() );

do_action( 'stm_lms_template_main_after' );
