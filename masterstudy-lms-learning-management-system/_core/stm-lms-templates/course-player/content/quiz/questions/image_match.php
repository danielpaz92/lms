<?php

/**
 * @var array $data
 * @var boolean $show_answers
 * @var int $item_id
 * @var boolean $dark_mode
 */

$data = apply_filters( 'masterstudy_lms_course_player_image_match_question', $data );
?>

<div class="masterstudy-course-player-image-match <?php echo esc_attr( $show_answers ? 'masterstudy-course-player-image-match_not-drag' : '' ); ?> <?php echo esc_attr( 'masterstudy-course-player-image-match_style-' . $data['question_view_type'] ); ?>">
	<?php
	foreach ( $data['answers'] as $i => $answer ) {
		if ( $show_answers ) {
			$data['correctly']    = isset( $data['last_answers']['user_answer'][ $i ]['text_image']['url'] ) ? trim( $data['last_answers']['user_answer'][ $i ]['text_image']['url'] ) === trim( $answer['text_image']['url'] ) : false;
			$data['wrongly']      = isset( $data['last_answers']['user_answer'][ $i ]['text_image']['url'] ) ? trim( $data['last_answers']['user_answer'][ $i ]['text_image']['url'] ) !== trim( $answer['text_image']['url'] ) : true;
			$data['answer_class'] = implode(
				' ',
				array_filter(
					array(
						$data['correctly'] ? 'masterstudy-course-player-image-match__question_correct' : '',
						$data['wrongly'] ? 'masterstudy-course-player-image-match__question_wrong' : '',
						'masterstudy-course-player-image-match__question_full',
					)
				)
			);
		}
		?>
		<div class="masterstudy-course-player-image-match__question <?php echo esc_attr( isset( $data['answer_class'] ) ? $data['answer_class'] : '' ); ?>">
			<div class="masterstudy-course-player-image-match__question-wrapper">
				<div class="masterstudy-course-player-image-match__question-content">
					<img src="<?php echo esc_url( ! empty( $answer['question_image']['url'] ) ? $answer['question_image']['url'] : STM_LMS_URL . '/assets/img/image_not_found.png' ); ?>"/>
					<?php if ( ! empty( $answer['question'] ) ) { ?>
						<div class="masterstudy-course-player-image-match__question-text">
							<?php echo wp_kses_post( $answer['question'] ); ?>
						</div>
					<?php } ?>
				</div>
				<div class="masterstudy-course-player-image-match__question-answer-wrapper">
					<div class="masterstudy-course-player-image-match__question-answer">
						<?php if ( $show_answers && ! empty( $data['last_answers']['user_answer'][ $i ] ) ) { ?>
							<div class="masterstudy-course-player-image-match__answer-item">
								<div class="masterstudy-course-player-image-match__answer-item-content">
									<div class="masterstudy-course-player-image-match__answer-item-image">
										<div class="masterstudy-course-player-image-match__answer-item-status">
											<?php if ( $data['correctly'] ) { ?>
												<span class="masterstudy-correctly"></span>
											<?php } elseif ( $data['wrongly'] ) { ?>
												<span class="masterstudy-wrongly"></span>
											<?php } ?>
										</div>
										<img src="<?php echo esc_url( ! empty( $data['last_answers']['user_answer'][ $i ]['text_image']['url'] ) ? $data['last_answers']['user_answer'][ $i ]['text_image']['url'] : STM_LMS_URL . '/assets/img/image_not_found.png' ); ?>"/>
									</div>
									<div class="masterstudy-course-player-image-match__answer-item-text-wrapper">
										<?php if ( ! empty( $data['last_answers']['user_answer'][ $i ]['text'] ) ) { ?>
											<div class="masterstudy-course-player-image-match__answer-item-text">
												<?php echo esc_html( $data['last_answers']['user_answer'][ $i ]['text'] ); ?>
											</div>
											<?php
										}
										if ( ! empty( $data['last_answers']['user_answer'][ $i ]['explain'] ) ) {
											?>
											<div class="masterstudy-course-player-image-match__answer-item-hint">
												<?php
												STM_LMS_Templates::show_lms_template(
													'components/hint',
													array(
														'content'   => $data['last_answers']['user_answer'][ $i ]['explain'],
														'side'      => 'right',
														'dark_mode' => $dark_mode,
													)
												);
												?>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
							<?php
						} elseif ( $show_answers && empty( $data['last_answers']['user_answer'][ $i ] ) ) {
							?>
							<span class="masterstudy-course-player-image-match__question-answer-wrongly"></span>
						<?php } ?>
					</div>
					<span class="masterstudy-course-player-image-match__question-answer-drag-text <?php echo esc_attr( ( $show_answers ) ? 'masterstudy-course-player-image-match__question-answer-drag-text_hide' : '' ); ?>">
						<?php echo esc_html__( 'Drag answer here', 'masterstudy-lms-learning-management-system' ); ?>
					</span>
				</div>
			</div>
		</div>
	<?php } ?>
	<input type="text" class="masterstudy-course-player-image-match__input" name="<?php echo esc_attr( $data['id'] ); ?>"/>
	<div class="masterstudy-course-player-image-match__answer <?php echo esc_attr( $show_answers ? 'masterstudy-course-player-image-match__answer_hide' : '' ); ?>">
		<?php
		shuffle( $data['answers'] );
		foreach ( $data['answers'] as $answer ) {
			?>
			<div class="masterstudy-course-player-image-match__answer-item">
				<div class="masterstudy-course-player-image-match__answer-item-content">
					<div class="masterstudy-course-player-image-match__answer-item-image">
						<img src="<?php echo esc_url( ! empty( $answer['text_image']['url'] ) ? $answer['text_image']['url'] : STM_LMS_URL . '/assets/img/image_not_found.png' ); ?>"/>
					</div>
					<div class="masterstudy-course-player-image-match__answer-item-container <?php echo esc_attr( empty( $answer['text'] ) ? 'masterstudy-course-player-image-match__answer-item-container_hide' : '' ); ?>">
						<div class="masterstudy-course-player-image-match__answer-item-drag"></div>
						<div class="masterstudy-course-player-image-match__question-answer-text-wrapper">
							<div class="masterstudy-course-player-image-match__answer-item-text">
								<?php echo esc_html( ! empty( $answer['text'] ) ? $answer['text'] : '' ); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
</div>
