<?php
/**
 * Course Player Template Hooks
 */

defined( 'ABSPATH' ) || exit;

/* Register all scripts used in course player  */
add_action( 'masterstudy_lms_course_player_register_scripts', 'masterstudy_lms_course_player_register_scripts', 10 );

/* Register all styles used in course player  */
add_action( 'masterstudy_lms_course_player_register_styles', 'masterstudy_lms_course_player_register_styles', 10 );

/* Update user current lesson */
add_action( 'masterstudy_lms_course_player_update_user_current_lesson', 'masterstudy_lms_course_player_update_user_current_lesson', 10, 2 );

/* Get data for main template */
add_filter( 'masterstudy_lms_course_player_main_template_data', 'masterstudy_lms_course_player_main_template_data', 10, 2 );

/* Get data for navigation template */
add_filter( 'masterstudy_lms_course_player_navigation_data', 'masterstudy_lms_course_player_navigation_data', 10, 4 );

/* Get lesson content from query */
add_filter( 'masterstudy_lms_course_player_lesson_query', 'masterstudy_lms_course_player_lesson_query', 10, 1 );

/* Get lesson materials */
add_filter( 'masterstudy_lms_course_player_lesson_materials', 'masterstudy_lms_course_player_lesson_materials', 10, 1 );

/* Get lesson video data */
add_filter( 'masterstudy_lms_course_player_lesson_video', 'masterstudy_lms_course_player_lesson_video', 10, 1 );

/* Get quiz content & data from query */
add_filter( 'masterstudy_lms_course_player_quiz_query', 'masterstudy_lms_course_player_quiz_query', 10, 2 );

/* Get question data */
add_filter( 'masterstudy_lms_course_player_question_data', 'masterstudy_lms_course_player_question_data', 10, 3 );

/* Get question bank query */
add_filter( 'masterstudy_lms_course_player_question_bank_query', 'masterstudy_lms_course_player_question_bank_query', 10, 2 );

/* Get question bank data */
add_filter( 'masterstudy_lms_course_player_question_bank_data', 'masterstudy_lms_course_player_question_bank_data', 10, 2 );

/* Get single choice question data */
add_filter( 'masterstudy_lms_course_player_single_choice_question', 'masterstudy_lms_course_player_single_choice_question', 10, 3 );

/* Get multi choice question data */
add_filter( 'masterstudy_lms_course_player_multi_choice_question', 'masterstudy_lms_course_player_multi_choice_question', 10, 3 );

/* Get item match question data */
add_filter( 'masterstudy_lms_course_player_item_match_question', 'masterstudy_lms_course_player_item_match_question', 10, 1 );

/* Get image match question data */
add_filter( 'masterstudy_lms_course_player_image_match_question', 'masterstudy_lms_course_player_image_match_question', 10, 1 );

/* Get true false question data */
add_filter( 'masterstudy_lms_course_player_true_false_question', 'masterstudy_lms_course_player_true_false_question', 10, 3 );

/* Get course completed */
add_filter( 'masterstudy_lms_course_player_completed', 'masterstudy_lms_course_player_completed', 10, 2 );

/* Get quiz fill the gap */
add_filter( 'masterstudy_lms_course_player_fill_the_gap', 'masterstudy_lms_course_player_fill_the_gap', 10, 2 );

/* Get quiz keywords */
add_filter( 'masterstudy_lms_course_player_quiz_keywords', 'masterstudy_lms_course_player_quiz_keywords', 10, 2 );
