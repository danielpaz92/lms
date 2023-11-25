<?php

/**
 * @var array $data
 * @var array $last_answers
 * @var boolean $show_answers
 * @var array $question_banks
 * @var string $quiz_style
 * @var int $item_id
 * @var boolean $dark_mode
 */

wp_enqueue_style( 'masterstudy-course-player-question' );
wp_enqueue_script( 'masterstudy-course-player-question' );

global $ms_question_number;

$data            = apply_filters( 'masterstudy_lms_course_player_question_data', $data, $last_answers, $item_id );
$types           = array( 'image_match', 'item_match' );
$classes         = implode(
	' ',
	array_filter(
		array(
			'pagination' === $quiz_style && 1 !== $ms_question_number ? 'masterstudy-course-player-question_hide' : '',
			( 'question_bank' !== $data['type'] ) ? ( $data['is_correct'] && $show_answers ? 'masterstudy-course-player-question_correct' : '' ) : '',
			( 'question_bank' !== $data['type'] ) ? ( ! $data['is_correct'] && ! empty( $data['last_answers']['user_answer'] ) ? 'masterstudy-course-player-question_wrong' : '' ) : '',
			'question_bank' === $data['type'] ? 'masterstudy-course-player-question_question-bank' : '',
		)
	)
);
$content_classes = implode(
	' ',
	array_filter(
		array(
			in_array( $data['type'], $types, true ) || ! empty( $data['has_image_question'] ) ? 'masterstudy-course-player-question__content_table-type' : '',
			'question_bank' === $data['type'] ? 'masterstudy-course-player-question__content_bank' : '',
		)
	)
);

if ( ! empty( $data['type'] ) && ! empty( $data['answers'] ) ) {
	?>
	<div class="masterstudy-course-player-question <?php echo esc_attr( $classes ); ?>"
		data-number-question="<?php echo esc_attr( 'question_bank' !== $data['type'] ? $ms_question_number : '' ); ?>">
		<?php if ( 'question_bank' !== $data['type'] ) { ?>
			<div class="masterstudy-course-player-question__header">
				<h3 class="masterstudy-course-player-question__title">
					<?php echo esc_html( $ms_question_number . '. ' . $data['title'] ); ?>
				</h3>
				<?php
				if ( ! empty( $data['content'] ) ) {
					?>
					<div class="masterstudy-course-player-question__description">
						<?php echo wp_kses_post( $data['content'] ); ?>
					</div>
					<?php
				} if ( ! empty( $data['image_url'] ) ) {
					?>
					<img class="masterstudy-course-player-question__image" src="<?php echo esc_url( $data['image_url'] ); ?>" />
					<?php
				}
				if ( ! empty( $data['question_explanation'] ) && $show_answers ) {
					?>
					<div class="masterstudy-course-player-question__explanation">
						<?php echo esc_html( $data['question_explanation'] ); ?>
					</div>
				<?php } ?>
			</div>
		<?php } ?>
		<div class="masterstudy-course-player-question__content <?php echo esc_attr( $content_classes ); ?>">
			<?php
			STM_LMS_Templates::show_lms_template(
				'course-player/content/quiz/questions/' . $data['type'],
				array(
					'data'           => $data,
					'show_answers'   => $show_answers,
					'last_answers'   => $last_answers,
					'quiz_style'     => $quiz_style,
					'question_banks' => ! empty( $question_banks ) ? $question_banks : array(),
					'item_id'        => $item_id,
					'dark_mode'      => $dark_mode,
				)
			);
			?>
		</div>
	</div>
	<?php
	$ms_question_number++;
}
