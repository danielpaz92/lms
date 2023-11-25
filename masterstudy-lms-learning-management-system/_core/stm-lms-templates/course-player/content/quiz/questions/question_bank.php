<?php

/**
 * @var array $data
 * @var array $last_answers
 * @var boolean $show_answers
 * @var string $quiz_style
 * @var array $question_banks
 * @var int $item_id
 * @var boolean $dark_mode
 */

$questions = apply_filters( 'masterstudy_lms_course_player_question_bank_data', $data, $question_banks );

if ( ! empty( $questions ) ) { ?>
	<div class="masterstudy-course-player-question-bank">
		<?php foreach ( $questions as $question ) { ?>
			<input type="hidden" name="questions_sequency[<?php echo esc_attr( $data['id'] ); ?>][]" value="<?php echo esc_attr( $question['id'] ); ?>" />
			<?php
			if ( ! empty( $question['type'] ) && ! empty( $question['answers'] ) ) {
				STM_LMS_Templates::show_lms_template(
					'course-player/content/quiz/questions/main',
					array(
						'data'         => $question,
						'last_answers' => $last_answers,
						'show_answers' => $show_answers,
						'quiz_style'   => $quiz_style,
						'item_id'      => $item_id,
						'dark_mode'    => $dark_mode,
					)
				);
			}
		}
		?>
	</div>
	<?php
	global $ms_question_number;
	$ms_question_number--;
}
