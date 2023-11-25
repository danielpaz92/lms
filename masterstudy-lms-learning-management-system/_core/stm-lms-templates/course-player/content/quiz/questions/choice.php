<?php

/**
 * @var array $data
 * @var boolean $show_answers
 * @var int $item_id
 * @var boolean $dark_mode
 * @var string $choice
 */

$input_type = 'multi' === $choice ? 'checkbox' : 'radio';

foreach ( $data['answers'] as $answer ) {
	$answer = apply_filters( "masterstudy_lms_course_player_{$choice}_choice_question", $data, $answer, $show_answers );
	?>
	<div class="masterstudy-course-player-answer <?php echo esc_attr( $data['has_image_question'] ? 'masterstudy-course-player-answer_image' : '' ); ?> <?php echo esc_attr( isset( $answer['answer_class'] ) ? $answer['answer_class'] : '' ); ?> <?php echo esc_attr( $show_answers ? 'masterstudy-course-player-answer_show-answers' : '' ); ?>">
		<div class="masterstudy-course-player-answer__input">
			<input type="<?php echo esc_attr( $input_type ); ?>" name="<?php echo esc_attr( $data['id'] ); ?><?php echo 'multi' === $choice ? '[]' : ''; ?>" value="<?php echo wp_kses_post( isset( $answer['full_answer'] ) ? $answer['full_answer'] : '' ); ?>"/>
			<span class="masterstudy-course-player-answer__<?php echo esc_attr( $input_type ); ?> <?php echo esc_attr( ( $answer['correctly'] || $answer['wrongly'] ) ? "masterstudy-course-player-answer__{$input_type}_checked" : '' ); ?>"></span>
			<?php if ( $data['has_image_question'] ) { ?>
				<img src="<?php echo esc_url( $answer['image_url'] ); ?>" class="masterstudy-course-player-answer__image"/>
				<?php
				if ( ! empty( $answer['explain'] ) && $show_answers ) {
					?>
					<div class="masterstudy-course-player-answer__hint">
						<?php
						STM_LMS_Templates::show_lms_template(
							'components/hint',
							array(
								'content'   => $answer['explain'],
								'side'      => 'right',
								'dark_mode' => $dark_mode,
							)
						);
						?>
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
			}
			?>
		</div>
		<div class="masterstudy-course-player-answer__wrapper">
			<?php if ( ! empty( $answer['text'] ) ) { ?>
				<div class="masterstudy-course-player-answer__text">
					<?php echo wp_kses_post( $answer['text'] ); ?>
				</div>
				<?php
			}

			if ( ! empty( $answer['explain'] ) && ! $data['has_image_question'] && $show_answers ) {
				?>
				<div class="masterstudy-course-player-answer__hint">
					<?php
					STM_LMS_Templates::show_lms_template(
						'components/hint',
						array(
							'content'   => $answer['explain'],
							'side'      => 'right',
							'dark_mode' => $dark_mode,
						)
					);
					?>
				</div>
				<?php
			}

			if ( $show_answers && ! $data['has_image_question'] ) {
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
