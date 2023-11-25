<?php

/**
 * @var array $data
 * @var boolean $show_answers
 * @var int $item_id
 * @var boolean $dark_mode
 */

foreach ( $data['answers'] as $answer ) {
	$answer = apply_filters( 'masterstudy_lms_course_player_true_false_question', $data, $answer, $show_answers );
	?>
	<div class="masterstudy-course-player-answer <?php echo esc_attr( isset( $answer['answer_class'] ) ? $answer['answer_class'] : '' ); ?> <?php echo esc_attr( $show_answers ? 'masterstudy-course-player-answer_show-answers' : '' ); ?>">
		<div class="masterstudy-course-player-answer__input">
			<input type="radio" name="<?php echo esc_attr( $data['id'] ); ?>" value="<?php echo esc_attr( $answer['text'] ); ?>"/>
			<span class="masterstudy-course-player-answer__radio <?php echo esc_attr( ( $answer['correctly'] || $answer['wrongly'] ) ? 'masterstudy-course-player-answer__radio_checked' : '' ); ?>"></span>
		</div>
		<div class="masterstudy-course-player-answer__wrapper">
			<?php if ( ! empty( $answer['text'] ) ) { ?>
				<div class="masterstudy-course-player-answer__text">
					<?php echo esc_html( $answer['text'] ); ?>
				</div>
				<?php
			}
			if ( $show_answers ) {
				if ( $answer['correctly'] ) {
					?>
					<div class="masterstudy-course-player-answer__status-correct">
						<span class="masterstudy-correctly"></span>
					</div>
					<?php
				} elseif ( $answer['wrongly'] ) {
					?>
					<div class="masterstudy-course-player-answer__status-wrong">
						<span class="masterstudy-wrongly"></span>
					</div>
					<?php
				}
			}
			?>
		</div>
	</div>
	<?php
}
