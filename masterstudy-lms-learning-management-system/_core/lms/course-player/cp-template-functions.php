<?php
/**
 * Course Player Template Functions
 */

defined( 'ABSPATH' ) || exit;

use MasterStudy\Lms\Repositories\CurriculumMaterialRepository;
use MasterStudy\Lms\Repositories\CurriculumRepository;
use MasterStudy\Lms\Repositories\CurriculumSectionRepository;

/* Register all scripts used in course player */
function masterstudy_lms_course_player_register_scripts() {
	wp_register_script( 'masterstudy-course-player-header', STM_LMS_URL . '/assets/js/course-player/header.js', array( 'jquery' ), MS_LMS_VERSION, true );
	wp_register_script( 'masterstudy-course-player-navigation', STM_LMS_URL . '/assets/js/course-player/navigation.js', array( 'jquery' ), MS_LMS_VERSION, true );
	wp_register_script( 'masterstudy-course-player-lesson', STM_LMS_URL . '/assets/js/course-player/content/lesson/lesson.js', array( 'jquery' ), MS_LMS_VERSION, true );
	wp_register_script( 'masterstudy-course-player-lesson-materials', STM_LMS_URL . '/assets/js/course-player/content/lesson/lesson-materials.js', array( 'jquery' ), MS_LMS_VERSION, true );
	wp_register_script( 'masterstudy-course-player-curriculum', STM_LMS_URL . '/assets/js/course-player/curriculum.js', array( 'jquery' ), MS_LMS_VERSION, true );
	wp_register_script( 'masterstudy-course-player-discussions', STM_LMS_URL . '/assets/js/course-player/discussions.js', array( 'jquery' ), MS_LMS_VERSION, true );
	wp_register_script( 'masterstudy-course-player-lesson-video', STM_LMS_URL . '/assets/js/course-player/content/lesson/lesson-video.js', array( 'jquery' ), MS_LMS_VERSION, true );
	wp_register_script( 'masterstudy-course-player-quiz-touch', STM_LMS_URL . '/assets/js/jquery.ui.touch-punch.min.js', array( 'jquery-ui-sortable' ), MS_LMS_VERSION, true );
	wp_register_script( 'masterstudy-course-player-quiz', STM_LMS_URL . '/assets/js/course-player/content/quiz.js', array( 'jquery', 'jquery-ui-sortable' ), MS_LMS_VERSION, true );
	wp_register_script( 'masterstudy-course-player-question', STM_LMS_URL . '/assets/js/course-player/content/questions.js', array( 'jquery' ), MS_LMS_VERSION, true );
	wp_register_script( 'masterstudy-course-player-course-completed', STM_LMS_URL . '/assets/js/course-player/course-completed.js', array( 'jquery' ), MS_LMS_VERSION, true );
	wp_register_script( 'jspdf', STM_LMS_URL . '/assets/vendors/jspdf.umd.js', array(), MS_LMS_VERSION, false );
	wp_register_script( 'masterstudy-course-player-certificate', STM_LMS_URL . '/assets/js/course-player/generate-certificate.js', array( 'jspdf', 'stm_certificate_fonts' ), MS_LMS_VERSION, false );
}

/* Register all styles used in course player */
function masterstudy_lms_course_player_register_styles() {
	wp_register_style( 'masterstudy-course-player-fonts', STM_LMS_URL . 'assets/css/course-player/fonts.css', null, MS_LMS_VERSION );
	wp_register_style( 'masterstudy-course-player-main', STM_LMS_URL . 'assets/css/course-player/main.css', null, MS_LMS_VERSION );
	wp_register_style( 'masterstudy-course-player-header', STM_LMS_URL . 'assets/css/course-player/header.css', null, MS_LMS_VERSION );
	wp_register_style( 'masterstudy-course-player-curriculum', STM_LMS_URL . 'assets/css/course-player/curriculum.css', null, MS_LMS_VERSION );
	wp_register_style( 'masterstudy-course-player-discussions', STM_LMS_URL . 'assets/css/course-player/discussions.css', null, MS_LMS_VERSION );
	wp_register_style( 'masterstudy-course-player-navigation', STM_LMS_URL . 'assets/css/course-player/navigation.css', null, MS_LMS_VERSION );
	wp_register_style( 'masterstudy-course-player-lesson', STM_LMS_URL . 'assets/css/course-player/content/lesson/main.css', null, MS_LMS_VERSION );
	wp_register_style( 'masterstudy-course-player-lesson-materials', STM_LMS_URL . 'assets/css/course-player/content/lesson/materials.css', null, MS_LMS_VERSION );
	wp_register_style( 'masterstudy-course-player-lesson-video', STM_LMS_URL . 'assets/css/course-player/content/lesson/video.css', null, MS_LMS_VERSION );
	wp_register_style( 'masterstudy-course-player-quiz', STM_LMS_URL . 'assets/css/course-player/content/quiz.css', null, MS_LMS_VERSION );
	wp_register_style( 'masterstudy-course-player-question', STM_LMS_URL . 'assets/css/course-player/content/questions.css', null, MS_LMS_VERSION );
	wp_register_style( 'masterstudy-course-player-course-completed', STM_LMS_URL . 'assets/css/course-player/course-completed.css', null, MS_LMS_VERSION );
	wp_register_style( 'masterstudy-course-player-locked', STM_LMS_URL . 'assets/css/course-player/locked.css', null, MS_LMS_VERSION );
}

/* Get data for main template */
function masterstudy_lms_course_player_main_template_data( $lesson_id, $lms_page_path ) {
	$course   = get_page_by_path( $lms_page_path, OBJECT, 'stm-courses' );
	$post_id  = apply_filters( 'wpml_object_id', $course->ID, 'post' );
	$item_id  = intval( $lesson_id );
	$user     = STM_LMS_User::get_current_user();
	$settings = get_option( 'stm_lms_settings' );
	$data     = array(
		'post_id'                  => $post_id,
		'item_id'                  => $item_id,
		'curriculum'               => ( new CurriculumRepository() )->get_curriculum( $post_id, true ),
		'material_ids'             => ( new CurriculumMaterialRepository() )->get_course_materials( $post_id ),
		'current_material'         => ( new CurriculumMaterialRepository() )->find_by_course_lesson( $post_id, $item_id ),
		'section'                  => null,
		'content_type'             => get_post_type( $item_id ),
		'stm_lms_question_sidebar' => apply_filters( 'stm_lms_show_question_sidebar', true ),
		'lesson_type'              => '',
		'course_title'             => get_the_title( $post_id ),
		'custom_css'               => get_post_meta( $item_id, '_wpb_shortcodes_custom_css', true ),
		'user'                     => $user,
		'has_access'               => STM_LMS_User::has_course_access( $post_id, $item_id ),
		'has_preview'              => STM_LMS_Lesson::lesson_has_preview( $item_id ),
		'is_trial_course'          => get_post_meta( $post_id, 'shareware', true ),
		'trial_lesson_count'       => 0,
		'has_trial_access'         => false,
		'is_enrolled'              => is_user_logged_in() ? STM_LMS_Course::get_user_course( $user['id'], $post_id ) : false,
		'user_page_url'            => STM_LMS_User::user_page_url(),
		'course_url'               => get_the_permalink( $post_id ),
		'lesson_lock_before_start' => false,
		'lesson_locked_by_drip'    => false,
		'is_scorm_course'          => false,
		'last_lesson'              => STM_LMS_Lesson::get_last_lesson( $post_id ),
		'show_logo'                => $settings['course_player_brand_icon_navigation'] ?? false,
		'logo_url'                 => ! empty( $settings['course_player_brand_icon_navigation_image'] ) ? wp_get_attachment_image_url( $settings['course_player_brand_icon_navigation_image'], 'thumbnail' ) : STM_LMS_URL . '/assets/img/image_not_found.png',
		'theme_fonts'              => $settings['course_player_theme_fonts'] ?? false,
		'discussions_sidebar'      => $settings['course_player_discussions_sidebar'] ?? true,
	);
	if ( is_user_logged_in() ) {
		$user_mode         = get_user_meta( $user['id'], 'masterstudy_course_player_theme_mode', true );
		$data['dark_mode'] = metadata_exists( 'user', $user['id'], 'masterstudy_course_player_theme_mode' ) ? $user_mode : $settings['course_player_theme_mode'] ?? false;
	} else {
		$data['dark_mode'] = $settings['course_player_theme_mode'] ?? false;
	}
	$content_types             = array(
		'stm-lessons'      => 'lesson',
		'stm-quizzes'      => 'quiz',
		'stm-assignments'  => 'assignments',
		'stm-google-meets' => 'google_meet',
	);
	$lesson_types              = array(
		'lesson'      => get_post_meta( $data['item_id'], 'type', true ),
		'assignments' => 'assignments',
		'quiz'        => 'quiz',
		'google_meet' => 'google_meet',
	);
	$lesson_types_labels       = array(
		'text'            => __( 'Text lesson', 'masterstudy-lms-learning-management-system' ),
		'video'           => __( 'Video lesson', 'masterstudy-lms-learning-management-system' ),
		'quiz'            => __( 'Quiz', 'masterstudy-lms-learning-management-system' ),
		'assignments'     => __( 'Assignment', 'masterstudy-lms-learning-management-system' ),
		'stream'          => __( 'Stream lesson', 'masterstudy-lms-learning-management-system' ),
		'zoom_conference' => __( 'Zoom lesson', 'masterstudy-lms-learning-management-system' ),
		'google_meet'     => __( 'Google Meet webinar', 'masterstudy-lms-learning-management-system' ),
	);
	$data['content_type']      = ( isset( $content_types[ $data['content_type'] ] ) ) ? $content_types[ $data['content_type'] ] : $data['content_type'];
	$data['lesson_type']       = ( isset( $lesson_types[ $data['content_type'] ] ) ) ? $lesson_types[ $data['content_type'] ] : $data['lesson_type'];
	$data['lesson_type_label'] = ( isset( $lesson_types_labels[ $data['lesson_type'] ] ) ) ? $lesson_types_labels[ $data['lesson_type'] ] : '';

	if ( ! empty( $data['current_material'] ) ) {
		$data['section'] = ( new CurriculumSectionRepository() )->find( $data['current_material']->section_id );
	}

	if ( class_exists( 'STM_LMS_Sequential_Drip_Content' ) ) {
		$settings = STM_LMS_Sequential_Drip_Content::stm_lms_get_settings();
		if ( ! empty( $settings['lock_before_start'] ) && ! STM_LMS_Sequential_Drip_Content::is_lesson_started( $item_id, $post_id ) ) {
			$data['lesson_lock_before_start'] = true;
		}
		if ( STM_LMS_Sequential_Drip_Content::lesson_is_locked( $post_id, $item_id ) ) {
			$data['lesson_locked_by_drip'] = true;
		}
	}

	if ( class_exists( 'STM_LMS_Scorm_Packages' ) ) {
		$data['is_scorm_course'] = STM_LMS_Scorm_Packages::is_scorm_course( $post_id );
	}

	if ( ! empty( $data['is_trial_course'] ) && 'on' === $data['is_trial_course'] ) {
		$data['course_materials']   = ( new CurriculumMaterialRepository() )->get_course_materials( $data['post_id'], false );
		$data['shareware_settings'] = get_option( 'stm_lms_shareware_settings' );
		$data['trial_lesson_count'] = $data['shareware_settings']['shareware_count'] ?? 0;
		$data['trial_lessons']      = array_filter(
			$data['course_materials'],
			function ( $lesson ) use ( $data ) {
				return ( $data['trial_lesson_count'] >= $lesson['order'] && $lesson['post_id'] === $data['item_id'] );
			}
		);
		if ( ! empty( $data['trial_lessons'] ) ) {
			$data['has_trial_access'] = true;
		}
	}

	return $data;
}

/* Update user current lesson */
function masterstudy_lms_course_player_update_user_current_lesson( $course_id, $item_id ) {
	$user = STM_LMS_User::get_current_user();

	if ( empty( $user['id'] ) ) {
		return false;
	}

	global $wpdb;

	$wpdb->update(
		stm_lms_user_courses_name( $wpdb ),
		array( 'current_lesson_id' => $item_id ),
		array(
			'user_id'   => $user['id'],
			'course_id' => $course_id,
		),
		array( '%d' ),
		array( '%d' )
	);
}

/* Get lesson content from query */
function masterstudy_lms_course_player_lesson_query( $item_id ) {
	return new WP_Query(
		array(
			'posts_per_page' => 1,
			'post_type'      => 'stm-lessons',
			'post__in'       => array( $item_id ),
		)
	);
}

/* Get quiz content & data from query */
function masterstudy_lms_course_player_quiz_query( $item_id, $post_id ) {
	$data    = array();
	$content = '';
	$query   = new WP_Query(
		array(
			'posts_per_page' => 1,
			'post_type'      => 'stm-quizzes',
			'post__in'       => array( $item_id ),
		)
	);

	if ( $query->have_posts() ) {
		$data = array(
			'quiz_meta'      => STM_LMS_Helpers::parse_meta_field( $item_id ),
			'user'           => STM_LMS_User::get_current_user(),
			'question_banks' => array(),
		);

		$data['last_quiz']        = isset( $data['user'] ) && ! empty( $data['user']['id'] ) ? STM_LMS_Helpers::simplify_db_array( stm_lms_get_user_last_quiz( $data['user']['id'], $item_id, array( 'progress' ) ) ) : '';
		$data['progress']         = ! empty( $data['last_quiz']['progress'] ) ? $data['last_quiz']['progress'] : 0;
		$data['passing_grade']    = isset( $data['quiz_meta'] ) && ! empty( $data['quiz_meta']['passing_grade'] ) ? $data['quiz_meta']['passing_grade'] : 0;
		$data['passed']           = $data['progress'] >= $data['passing_grade'] && ! empty( $data['progress'] );
		$data['duration']         = STM_LMS_Quiz::get_quiz_duration( $item_id );
		$data['duration_value']   = get_post_meta( $item_id, 'duration', true );
		$data['duration_measure'] = get_post_meta( $item_id, 'duration_measure', true );
		$data['quiz_style']       = STM_LMS_Quiz::get_style( $item_id );
		$data['show_answers']     = STM_LMS_Quiz::quiz_passed( $item_id ) || ( ! empty( $data['last_quiz'] ) && get_post_meta( $item_id, 'correct_answer', true ) );
		$data['random_questions'] = get_post_meta( $item_id, 'random_questions', true );

		while ( $query->have_posts() ) {
			$query->the_post();
			ob_start();
			the_content();
			$content = ob_get_clean();
			$content = str_replace( '../../', site_url() . '/', $content );
		}
		wp_reset_postdata();

		$data['content'] = $content;
	}

	if ( isset( $data['quiz_meta'] ) && ! empty( $data['quiz_meta']['questions'] ) ) {
		$args = array(
			'post_type'      => 'stm-questions',
			'posts_per_page' => -1,
			'post__in'       => array_map( 'stm_lms_get_wpml_binded_id', explode( ',', $data['quiz_meta']['questions'] ) ),
			'orderby'        => 'post__in',
		);
		if ( ! empty( $data['random_questions'] ) && 'on' === $data['random_questions'] ) {
			$args['orderby'] = 'rand';
		}

		$questions_query = new WP_Query( $args );

		if ( $questions_query->have_posts() ) {
			$data['passing_grade']      = get_post_meta( $item_id, 'passing_grade', true );
			$data['questions_quantity'] = $questions_query->found_posts;
			$data['questions_for_nav']  = $questions_query->found_posts;
			$data['questions']          = array();
			$data['quiz_info']          = stm_lms_get_user_quizzes( $data['user']['id'], $item_id );

			while ( $questions_query->have_posts() ) {
				$questions_query->the_post();
				$question_data           = array(
					'id'      => get_the_ID(),
					'title'   => get_the_title(),
					'content' => str_replace( '../../', site_url() . '/', stm_lms_filtered_output( get_the_content() ) ),
				);
				$data['questions_ids'][] = $question_data['id'];
				$question_meta           = STM_LMS_Helpers::parse_meta_field( $question_data['id'] );
				$question_data           = array_merge( $question_data, $question_meta );
				$data['questions'][]     = $question_data;

				if ( 'question_bank' === $question_data['type'] ) {
					$bank_data                                      = apply_filters( 'masterstudy_lms_course_player_question_bank_query', $question_data, $item_id );
					$data['question_banks'][ $question_data['id'] ] = $bank_data;
					if ( ! empty( $data['question_banks'] ) ) {
						$bank_limit                 = $data['question_banks'][ $question_data['id'] ]->found_posts > $question_data['answers'][0]['number'] ? $question_data['answers'][0]['number'] - 1 : $data['question_banks'][ $question_data['id'] ]->found_posts - 1;
						$data['questions_for_nav'] += $bank_limit;
					}
				}
			}
			wp_reset_postdata();

			if ( ! empty( $data['quiz_info'] ) ) {
				$quiz_info_last_element = end( $data['quiz_info'] );
				$sequency               = json_decode( $quiz_info_last_element['sequency'], true );
				if ( ! empty( $sequency ) && is_array( $sequency ) ) {
					$iteration = 0;
					foreach ( $sequency as $question ) {
						if ( is_array( $question ) ) {
							$data['questions_quantity'] += count( $question );
						}
						$iteration++;
					}
					$data['questions_quantity'] -= $iteration;
				}
			}

			$data['last_answers'] = stm_lms_get_quiz_latest_answers(
				$data['user']['id'],
				$item_id,
				$data['questions_quantity'],
				array(
					'question_id',
					'user_answer',
					'correct_answer',
				)
			);
			$data['last_answers'] = STM_LMS_Helpers::set_value_as_key( $data['last_answers'], 'question_id' );
		}
	}

	return $data;
}

/* Get question data */
function masterstudy_lms_course_player_question_data( $data, $last_answers, $item_id ) {
	$data['type']                = empty( $data['type'] ) ? 'single_choice' : $data['type'];
	$data['last_answers']        = $last_answers[ $data['id'] ] ?? array();
	$data['is_correct']          = ! empty( $data['last_answers']['correct_answer'] );
	$data['show_correct_answer'] = get_post_meta( $item_id, 'correct_answer', true );

	if ( $data['is_correct'] ) {
		$data['last_answers'] = array();
	}

	if ( ! empty( $data['type'] ) && ! empty( $data['answers'] ) ) {
		$data['image'] = ( isset( $data['image'] ) ) ? $data['image'] : array();
		if ( ! empty( $data['image'] ) ) {
			$data['image_url'] = wp_get_attachment_image_src( $data['image']['id'], 'full' );
			$data['image_url'] = $data['image_url'][0];
		}
		$data['has_image_question'] = ! empty( $data['question_view_type'] ) && 'image' === $data['question_view_type'];
		$data['correct_answer']     = false;
		if ( ! empty( $data['last_answers'] ) && ! empty( $data['last_answers']['correct_answer'] ) ) {
			$data['correct_answer'] = true;
		}
		if ( 'question_bank' === $data['type'] ) {
			$data['correct_answer'] = 'bank';
		}
	}

	return $data;
}

/* Get question bank query */
function masterstudy_lms_course_player_question_bank_query( $question_data, $item_id ) {
	if ( ! empty( $question_data['answers'][0] ) && ! empty( $question_data['answers'][0]['categories'] ) && ! empty( $question_data['answers'][0]['number'] ) ) {
		$questions_in_quiz = get_post_meta( $item_id, 'questions', true );
		$questions_in_quiz = ( ! empty( $questions_in_quiz ) ) ? explode( ',', $questions_in_quiz ) : array();
		$random            = get_post_meta( $item_id, 'random_questions', true );
		$bank_args         = array(
			'post_type'      => 'stm-questions',
			'posts_per_page' => $question_data['answers'][0]['number'],
			'post__not_in'   => $questions_in_quiz,
			'meta_query'     => array(
				array(
					'key'     => 'type',
					'value'   => 'question_bank',
					'compare' => '!=',
				),
			),
			'tax_query'      => array(
				array(
					'taxonomy' => 'stm_lms_question_taxonomy',
					'field'    => 'slug',
					'terms'    => wp_list_pluck( $question_data['answers'][0]['categories'], 'slug' ),
				),
			),
		);
		if ( ! empty( $random ) && 'on' === $random ) {
			$bank_args['orderby'] = 'rand';
		}
		return new WP_Query( $bank_args );
	}

	return array();
}

/* Get question bank data */
function masterstudy_lms_course_player_question_bank_data( $data, $question_banks ) {
	$question_bank_data = array();
	if ( ! empty( $question_banks[ $data['id'] ] ) && $question_banks[ $data['id'] ]->have_posts() ) {
		while ( $question_banks[ $data['id'] ]->have_posts() ) {
			$question_banks[ $data['id'] ]->the_post();
			$question_data        = array(
				'id'      => get_the_ID(),
				'title'   => get_the_title(),
				'content' => str_replace( '../../', site_url() . '/', stm_lms_filtered_output( get_the_content() ) ),
			);
			$question_meta        = STM_LMS_Helpers::parse_meta_field( $question_data['id'] );
			$question_bank_data[] = array_merge( $question_data, $question_meta );
		}
	}
	wp_reset_postdata();

	return $question_bank_data;
}

/* Get single choice question data */
function masterstudy_lms_course_player_single_choice_question( $info, $answer, $show_answers ) {
	$data = array(
		'isTrue'       => $answer['isTrue'] ?? false,
		'full_answer'  => ( ! empty( $answer['text_image']['url'] ) ) ? $answer['text'] . '|' . $answer['text_image']['url'] : $answer['text'],
		'image_url'    => ( ! empty( $answer['text_image']['url'] ) ) ? $answer['text_image']['url'] : STM_LMS_URL . '/assets/img/image_not_found.png',
		'correctly'    => false,
		'wrongly'      => false,
		'show_correct' => false,
	);

	if ( $show_answers ) {
		$info['last_answers'] = ( ! empty( $info['last_answers']['user_answer'] ) ) ? stripcslashes( $info['last_answers']['user_answer'] ) : '';
		$info['last_answers'] = ( $info['is_correct'] && $answer['isTrue'] ) ? $data['full_answer'] : $info['last_answers'];
		$data['correctly']    = $data['full_answer'] === $info['last_answers'] && $answer['isTrue'];
		$data['wrongly']      = $data['full_answer'] === $info['last_answers'] && ! $answer['isTrue'];
		$data['show_correct'] = $data['full_answer'] !== $info['last_answers'] && $answer['isTrue'] && $info['show_correct_answer'];
		$data['answer_class'] = implode(
			' ',
			array_filter(
				array(
					$data['correctly'] || $data['show_correct'] ? 'masterstudy-course-player-answer_correct' : '',
					$data['wrongly'] ? 'masterstudy-course-player-answer_wrong' : '',
				)
			)
		);
	}

	return array_merge( $answer, $data );
}

/* Get item match question data */
function masterstudy_lms_course_player_item_match_question( $data ) {
	if ( ! empty( $data['last_answers']['user_answer'] ) ) {
		$data['last_answers']['user_answer'] = explode( '[stm_lms_sep]', str_replace( '[stm_lms_item_match]', '', $data['last_answers']['user_answer'] ) );
		$data['last_answers']['user_answer'] = array_map(
			function ( $user_answer ) use ( $data ) {
				foreach ( $data['answers'] as $answer ) {
					if ( $user_answer === $answer['text'] ) {
						return $answer;
					}
				}
				return $user_answer;
			},
			$data['last_answers']['user_answer']
		);
	} elseif ( empty( $data['last_answers'] ) && $data['is_correct'] ) {
		$data['last_answers']['user_answer'] = $data['answers'];
	}

	return $data;
}

/* Get image match question data */
function masterstudy_lms_course_player_image_match_question( $data ) {
	if ( ! empty( $data['last_answers']['user_answer'] ) ) {
		$data['last_answers']['user_answer'] = explode( '[stm_lms_sep]', str_replace( '[stm_lms_image_match]', '', $data['last_answers']['user_answer'] ) );
		$data['last_answers']['user_answer'] = array_map(
			function ( $user_answer ) use ( $data ) {
				foreach ( $data['answers'] as $answer ) {
					if ( $user_answer === $answer['text'] . '|' . $answer['text_image']['url'] ) {
						return $answer;
					}
				}
				return $user_answer;
			},
			$data['last_answers']['user_answer']
		);
	} elseif ( empty( $data['last_answers'] ) && $data['is_correct'] ) {
		$data['last_answers']['user_answer'] = $data['answers'];
	}

	return $data;
}

/* Get multi choice question data */
function masterstudy_lms_course_player_multi_choice_question( $info, $answer, $show_answers ) {
	$data = array(
		'isTrue'       => $answer['isTrue'] ?? false,
		'full_answer'  => ( ! empty( $answer['text_image']['url'] ) ) ? trim( rawurldecode( $answer['text'] . '|' . $answer['text_image']['url'] ) ) : trim( rawurldecode( $answer['text'] ) ),
		'image_url'    => ( ! empty( $answer['text_image']['url'] ) ) ? $answer['text_image']['url'] : STM_LMS_URL . '/assets/img/image_not_found.png',
		'correctly'    => false,
		'wrongly'      => false,
		'show_correct' => false,
	);
	if ( $show_answers ) {
		$info['last_answers'] = ( ! empty( $info['last_answers']['user_answer'] ) ) ? array_map( 'rawurldecode', explode( ',', $info['last_answers']['user_answer'] ) ) : array();
		$info['last_answers'] = ( $info['is_correct'] && $answer['isTrue'] ) ? array( $data['full_answer'] ) : $info['last_answers'];
		$data['correctly']    = in_array( $data['full_answer'], $info['last_answers'], true ) && $answer['isTrue'];
		$data['wrongly']      = in_array( $data['full_answer'], $info['last_answers'], true ) && ! $answer['isTrue'];
		$data['show_correct'] = ! in_array( $data['full_answer'], $info['last_answers'], true ) && $answer['isTrue'] && $info['show_correct_answer'];
		$data['answer_class'] = implode(
			' ',
			array_filter(
				array(
					$data['correctly'] || $data['show_correct'] ? 'masterstudy-course-player-answer_correct' : '',
					$data['wrongly'] ? 'masterstudy-course-player-answer_wrong' : '',
				)
			)
		);
	}

	return array_merge( $answer, $data );
}

/* Get true false question data */
function masterstudy_lms_course_player_true_false_question( $info, $answer, $show_answers ) {
	$data = array(
		'isTrue'       => $answer['isTrue'] ?? false,
		'correctly'    => false,
		'wrongly'      => false,
		'show_correct' => false,
	);

	if ( $show_answers ) {
		$info['last_answers'] = ( isset( $info['last_answers']['user_answer'] ) ) ? $info['last_answers']['user_answer'] : '';
		$info['last_answers'] = ( $info['is_correct'] && $answer['isTrue'] ) ? $answer['text'] : $info['last_answers'];
		$data['correctly']    = $answer['text'] === $info['last_answers'] && $answer['isTrue'];
		$data['wrongly']      = $answer['text'] === $info['last_answers'] && ! $answer['isTrue'];
		$data['show_correct'] = $answer['text'] !== $info['last_answers'] && $answer['isTrue'] && $info['show_correct_answer'];
		$data['answer_class'] = implode(
			' ',
			array_filter(
				array(
					$data['correctly'] || $data['show_correct'] ? 'masterstudy-course-player-answer_correct' : '',
					$data['wrongly'] ? 'masterstudy-course-player-answer_wrong' : '',
				)
			)
		);
	}

	return array_merge( $answer, $data );
}

/* Get lesson materials */
function masterstudy_lms_course_player_lesson_materials( $ids ) {
	if ( ! empty( $ids ) ) {
		return get_posts(
			array(
				'post_type' => 'attachment',
				'include'   => $ids,
				'order'     => 'ASC',
			)
		);
	}

	return false;
}

function masterstudy_lms_course_player_navigation_data( $post_id, $item_id, $material_ids, $current_material ) {
	if ( 'stm-quizzes' === $current_material->post_type ) {
		$completed = ( STM_LMS_Quiz::quiz_passed( $item_id ) ) ? 'completed' : 'uncompleted';
	} else {
		$completed = ( STM_LMS_Lesson::is_lesson_completed( '', $post_id, $item_id ) ) ? 'completed' : 'uncompleted';
	}

	$completed_label     = 'completed' === $completed
		? esc_html__( 'Completed', 'masterstudy-lms-learning-management-system' )
		: esc_html__( 'Complete', 'masterstudy-lms-learning-management-system' );
	$current_lesson_id   = array_search( $item_id, $material_ids, true );
	$prev_lesson         = $material_ids[ $current_lesson_id - 1 ] ?? null;
	$prev_lesson_url     = '';
	$prev_lesson_preview = false;
	$next_lesson         = $material_ids[ $current_lesson_id + 1 ] ?? null;
	$next_lesson_url     = '';
	$next_lesson_preview = false;
	$completed_label     = apply_filters( 'stm_lms_completed_label', $completed_label, $item_id, $post_id );
	$is_assignment       = false;

	if ( method_exists( 'STM_LMS_Assignments', 'is_draft_assignment' ) ) {
		$is_assignment = STM_LMS_Assignments::is_draft_assignment( $item_id );
	}

	if ( ! empty( $prev_lesson ) ) {
		$prev_lesson_url     = esc_url( STM_LMS_Lesson::get_lesson_url( $post_id, $prev_lesson ) );
		$prev_lesson_preview = STM_LMS_Lesson::lesson_has_preview( $prev_lesson );
	}

	if ( ! empty( $next_lesson ) ) {
		$next_lesson_url     = esc_url( STM_LMS_Lesson::get_lesson_url( $post_id, $next_lesson ) );
		$next_lesson_preview = STM_LMS_Lesson::lesson_has_preview( $next_lesson );
	}

	return array(
		'completed'           => $completed,
		'completed_label'     => $completed_label,
		'prev_lesson'         => $prev_lesson,
		'prev_lesson_url'     => $prev_lesson_url,
		'next_lesson'         => $next_lesson,
		'next_lesson_url'     => $next_lesson_url,
		'prev_lesson_preview' => $prev_lesson_preview,
		'next_lesson_preview' => $next_lesson_preview,
		'is_assignment'       => $is_assignment,
	);
}

function masterstudy_lms_course_player_lesson_video( $post_id ) {
	$data = array(
		'video_type'        => get_post_meta( $post_id, 'video_type', true ),
		'presto_player_idx' => get_post_meta( $post_id, 'presto_player_idx', true ),
		'embed_ctx'         => str_replace( array( '<p>', '</p>' ), '', get_post_meta( $post_id, 'lesson_embed_ctx', true ) ),
		'ext_link_url'      => get_post_meta( $post_id, 'lesson_ext_link_url', true ),
		'youtube_url'       => get_post_meta( $post_id, 'lesson_youtube_url', true ),
		'vimeo_url'         => get_post_meta( $post_id, 'lesson_vimeo_url', true ),
		'video_poster'      => get_post_meta( $post_id, 'lesson_video_poster', true ),
		'video_url'         => get_post_meta( $post_id, 'lesson_video_url', true ),
		'video'             => get_post_meta( $post_id, 'lesson_video', true ),
		'video_width'       => get_post_meta( $post_id, 'lesson_video_width', true ),
		'shortcode'         => get_post_meta( $post_id, 'lesson_shortcode', true ),
		'allowed_sources'   => array_keys( ms_plugin_video_sources() ),
		'video_classes'     => '',
	);

	$data['poster']     = in_array( $data['video_type'], array( 'html', 'ext_link' ), true ) && ! empty( $data['video_poster'] ) ? stm_lms_get_image_url( $data['video_poster'] ) : '';
	$data['video_type'] = empty( $data['video_type'] ) && ! empty( $data['video'] ) ? 'html' : $data['video_type'];

	if ( in_array( $data['video_type'], array( 'html', 'ext_link' ), true ) ) {
		$data['uploaded_video'] = $data['ext_link_url'];
		$data['video_format']   = 'mp4';
		if ( 'html' === $data['video_type'] ) {
			$data['uploaded_video'] = wp_get_attachment_url( $data['video'] );
			$data['video_format']   = explode( '.', $data['uploaded_video'] );
			$data['video_format']   = strtolower( end( $data['video_format'] ) );
		}
	} elseif ( in_array( $data['video_type'], array( 'youtube', 'vimeo' ), true ) ) {
		$data['video_idx'] = 'youtube' === $data['video_type'] ? ms_plugin_get_youtube_id( $data['youtube_url'] ) : ms_plugin_get_vimeo_id( $data['vimeo_url'] );
		$data['youtube']   = 'https://www.youtube.com/embed/' . $data['video_idx'] . '?&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1';
		$data['vimeo']     = 'https://player.vimeo.com/video/' . $data['video_idx'] . '?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media';
	}

	return $data;
}

function masterstudy_lms_course_player_completed( $post_id, $item_id ) {
	$total_progress = STM_LMS_Lesson::get_total_progress( get_current_user_id(), $post_id );
	if ( ! empty( $total_progress ) ) {
		$progress_percent = $total_progress['course']['progress_percent'];
		$threshold        = STM_LMS_Options::get_option( 'certificate_threshold', 70 );
		$passed           = ( $progress_percent >= $threshold );
	}

	return array(
		'lesson_completed'        => STM_LMS_Lesson::is_lesson_completed( null, $post_id, $item_id ),
		'passed'                  => ! empty( $passed ) ? $passed : false,
		'disable_smile'           => STM_LMS_Options::get_option( 'finish_popup_image_disable', false ),
		'custom_failed_image_id'  => STM_LMS_Options::get_option( 'finish_popup_image_failed' ),
		'custom_success_image_id' => STM_LMS_Options::get_option( 'finish_popup_image_success' ),
		'failed_image'            => STM_LMS_URL . 'assets/icons/lessons/course-completed-negative.svg',
		'success_image'           => STM_LMS_URL . 'assets/icons/lessons/course-completed-positive.svg',
	);
}

/* Get quiz fill the gap */
function masterstudy_lms_course_player_fill_the_gap( $data, $show_answers ) {
	// Get fill data
	$data = array(
		'id'                       => $data['id'],
		'user_answer'              => ! empty( $data['last_answers']['user_answer'] ) ? explode( ',', $data['last_answers']['user_answer'] ) : array(),
		'text'                     => $data['answers'][0]['text'],
		'matches'                  => stm_lms_get_string_between( $data['answers'][0]['text'], '|', '|' ),
		'answer_field'             => array(),
		'correct_answer'           => array(),
		'correct_user_answer'      => array(),
		'show_correct_user_answer' => array(),
		'is_correct'               => $data['is_correct'],
	);

	$question_variables = array();
	$answer_variables   = array();

	if ( ! empty( $data['matches'] ) ) {
		// Get questions data
		$data_question = array_map(
			function ( $answer ) {
				return "|{$answer['answer']}|";
			},
			$data['matches']
		);

		foreach ( $data_question as $match_index => $match ) {
			$width                                = 'width: ' . ( strlen( $match ) * 8 + 16 ) . 'px';
			$name                                 = "{$data['id']}[{$match_index}]";
			$data['answer_field'][ $match_index ] = "<input type='text' name='{$name}' style='{$width}' />";
			$question_variables['answer_field'][ $match_index ] = $data['answer_field'][ $match_index ];
		}

		if ( $show_answers ) {
			// Get answers data
			foreach ( $data['matches'] as $match_index => $match ) {
				$match_index                         = (int) $match_index;
				$match_answer                        = stripslashes( rawurldecode( $match['answer'] ) );
				$data['user_answer'][ $match_index ] = isset( $data['user_answer'][ $match_index ] )
					? stripslashes( rawurldecode( $data['user_answer'][ $match_index ] ) )
					: null;

				$correct = ( isset( $data['user_answer'][ $match_index ] ) && strtolower( $match_answer ) === strtolower( $data['user_answer'][ $match_index ] ) || $data['is_correct'] )
					? 'masterstudy-course-player-fill-the-gap__check-correct'
					: 'masterstudy-course-player-fill-the-gap__check-incorrect';

				$data['correct_answer'][ $match_index ]           = "{$correct}";
				$data['correct_user_answer'][ $match_index ]      = "{$data['user_answer'][$match_index]}";
				$data['show_correct_user_answer'][ $match_index ] = "{$match_answer}";

				$answer_variables['correct_answer'][ $match_index ]           = $data['correct_answer'][ $match_index ];
				$answer_variables['correct_user_answer'][ $match_index ]      = ( $data['is_correct'] ) ? $match['answer'] : $data['correct_user_answer'][ $match_index ];
				$answer_variables['show_correct_user_answer'][ $match_index ] = $data['show_correct_user_answer'][ $match_index ];
			}
		}
	}

	return array_merge( $data, $question_variables, $answer_variables );
}

/* Get quiz keywords */
function masterstudy_lms_course_player_quiz_keywords( $data, $show_answers ) {
	$user_answers = array();

	if ( ! empty( $data['last_answers']['user_answer'] ) ) {
		$user_answers = explode( '[stm_lms_sep]', str_replace( '[stm_lms_keywords]', '', $data['last_answers']['user_answer'] ) );
	}

	$uniq_id     = uniqid( 'quiz_' );
	$answers_for = ( ! empty( $data['answers'] ) ) ? wp_list_pluck( $data['answers'], 'text' ) : array();

	return array(
		'id'                  => $data['id'],
		'show_answers'        => $show_answers,
		'answers'             => $data['answers'],
		'is_correct'          => $data['is_correct'],
		'user_answers'        => $user_answers,
		'show_correct_answer' => $data['show_correct_answer'],
		'uniq_id'             => $uniq_id,
		'uniq_id_script'      => wp_json_encode( array_map( 'strtolower', $answers_for ) ),
	);
}

/* User dark mode save */
function masterstudy_lms_course_player_user_dark_mode() {
	check_ajax_referer( 'masterstudy_lms_dark_mode', 'nonce' );

	$user = STM_LMS_User::get_current_user();
	if ( ! empty( $_POST['mode'] ) ) {
		$meta_value = 'false' === $_POST['mode'] ? false : sanitize_text_field( wp_unslash( $_POST['mode'] ) );
		update_user_meta( $user['id'], 'masterstudy_course_player_theme_mode', $meta_value );
	}
}
add_action( 'wp_ajax_masterstudy_lms_dark_mode', 'masterstudy_lms_course_player_user_dark_mode' );
