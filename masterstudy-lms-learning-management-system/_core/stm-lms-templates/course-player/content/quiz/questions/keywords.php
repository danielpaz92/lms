<?php
/**
 * @var array $data
 * @var boolean $show_answers
 * @var int $item_id
 * @var boolean $dark_mode
 */

$data = apply_filters( 'masterstudy_lms_course_player_quiz_keywords', $data, $show_answers );

if ( ! empty( $data['answers'] ) ) :
	wp_localize_script( 'masterstudy-course-player-question', $data['uniq_id'], json_decode( $data['uniq_id_script'] ) );
	?>
<div class="masterstudy-course-player-quiz-keywords" data-quiz-keywords="<?php echo esc_attr( $data['uniq_id'] ); ?>">
	<div class="masterstudy-course-player-quiz-keywords__questions <?php echo esc_html( $show_answers ? 'hidden' : '' ); ?>">
		<label>
			<?php echo esc_html__( 'Keyword', 'masterstudy-lms-learning-management-system' ); ?>
			<input type="text" class="masterstudy-course-player-quiz-keywords__keyword-to-fill" placeholder="<?php echo esc_html__( 'Type keyword here', 'masterstudy-lms-learning-management-system' ); ?>">
		</label>
		<span class="masterstudy-course-player-quiz-keywords__flying-word"></span>
		<input type="text" class="masterstudy-course-player-quiz-keywords__input" name="<?php echo esc_attr( $data['id'] ); ?>"/>
		<div class="masterstudy-course-player-quiz-keywords__answers">
			<?php foreach ( $data['answers'] as $key => $answer ) : ?>
				<div class="masterstudy-course-player-quiz-keywords__answer masterstudy-course-player-quiz-keywords__answer_<?php echo esc_attr( $key ); ?>">
					<div class="masterstudy-course-player-quiz-keywords__value">
						<?php
						/* translators: %s is a placeholder for the keyword number */
						printf( esc_html__( 'Keyword #%s', 'masterstudy-lms-learning-management-system' ), esc_html( $key ) + 1 );
						?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php if ( $data['show_answers'] ) : ?>
	<div class="masterstudy-course-player-quiz-keywords__answers masterstudy-course-player-quiz-keywords__user_answers">
		<?php
		foreach ( $data['answers'] as $key => $correct_answer ) :
			$is_correct = ( ! empty( $data['user_answers'][ $key ] ) && strtolower( $data['user_answers'][ $key ] ) === strtolower( $correct_answer['text'] ) || $data['is_correct'] ) ? 'correct' : 'incorrect';
			?>
			<div class="masterstudy-course-player-quiz-keywords__answer masterstudy-course-player-quiz-keywords__answer-<?php echo esc_attr( $is_correct ); ?>">
				<div class="masterstudy-course-player-quiz-keywords__answer-value">
					<?php
					if ( $data['show_correct_answer'] || $data['is_correct'] ) {
						echo esc_html( $correct_answer['text'] );
					} elseif ( ! empty( $data['user_answers'][ $key ] ) ) {
						echo esc_html( $data['user_answers'][ $key ] );
					} else {
						/* translators: %s is a placeholder for the keyword number */
						printf( esc_html__( 'Keyword #%s', 'masterstudy-lms-learning-management-system' ), esc_html( $key ) + 1 );
					}

					if ( ! empty( $correct_answer['explain'] ) && $show_answers ) :
						?>
					<div class="masterstudy-course-player-answer__hint">
						<?php
						STM_LMS_Templates::show_lms_template(
							'components/hint',
							array(
								'content'   => $correct_answer['explain'],
								'side'      => 'right',
								'dark_mode' => $dark_mode,
							)
						);
						?>
					</div>
					<?php endif; ?>
					<span class="masterstudy-course-player-answer__status <?php echo esc_attr( 'correct' === $is_correct || $data['is_correct'] ? 'masterstudy-correctly' : 'masterstudy-wrongly' ); ?>"></span>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>
</div>
	<?php
endif;
