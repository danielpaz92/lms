<?php
/**
 * @var array $user
 * @var object $comment
 */
?>
<div class="masterstudy-course-player-assignments__instructor-comment">
	<div class="masterstudy-course-player-assignments__instructor-comment-title">
		<?php echo esc_html__( 'Instructor comment:', 'masterstudy-lms-learning-management-system-pro' ); ?>
	</div>
	<div class="masterstudy-course-player-assignments__instructor-comment-wrapper">
		<div class="masterstudy-course-player-assignments__instructor-comment-image">
			<img src="<?php echo esc_url( $user['avatar_url'] ); ?>" class="masterstudy-course-player-assignments__instructor-comment-avatar">
		</div>
		<div class="masterstudy-course-player-assignments__instructor-comment-content">
			<span class="masterstudy-course-player-assignments__instructor-comment-name">
				<?php echo esc_html( $user['login'] ); ?>
			</span>
			<div class="masterstudy-course-player-assignments__instructor-comment-text">
				<?php echo wp_kses_post( $comment ); ?>
			</div>
		</div>
	</div>
</div>
