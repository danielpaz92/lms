<?php
/**
 * @var int $post_id
 * @var int $item_id
 * @var array $data
 */

$current_template = $data['current_template'];

if ( empty( $data['theme_fonts'] ) ) {
	wp_enqueue_style( 'masterstudy-course-player-assignments-fonts' );
}
wp_enqueue_style( 'masterstudy-course-player-assignments' );
wp_enqueue_script( 'masterstudy-course-player-assignments' );
wp_localize_script(
	'masterstudy-course-player-assignments',
	'assignments_data',
	array(
		'submit_nonce' => wp_create_nonce( 'stm_lms_accept_draft_assignment' ),
		'add_nonce'    => wp_create_nonce( 'stm_lms_upload_file_assignment' ),
		'delete_nonce' => wp_create_nonce( 'stm_lms_delete_assignment_file' ),
		'ajax_url'     => admin_url( 'admin-ajax.php' ),
		'icon_url'     => STM_LMS_URL . '/assets/icons/files/new/',
		'draft_id'     => $data['template_data'][ $current_template ]['id'],
		'course_id'    => $post_id,
		'editor_id'    => $data['editor_id'],
		'dark_mode'    => $data['dark_mode'],
	)
);

if ( 'passed' === $current_template || 'unpassed' === $current_template ) {
	STM_LMS_Assignments::student_view_update( $data['template_data'][ $current_template ]['id'] );
}

STM_LMS_Templates::show_lms_template(
	'components/alert',
	array(
		'id'                  => 'assignment_file_alert',
		'title'               => esc_html__( 'Delete file', 'masterstudy-lms-learning-management-system' ),
		'text'                => esc_html__( 'Are you sure you want to delete this file?', 'masterstudy-lms-learning-management-system-pro' ),
		'submit_button_text'  => esc_html__( 'Delete', 'masterstudy-lms-learning-management-system-pro' ),
		'cancel_button_text'  => esc_html__( 'Cancel', 'masterstudy-lms-learning-management-system-pro' ),
		'submit_button_style' => 'danger',
		'cancel_button_style' => 'tertiary',
		'dark_mode'           => $data['dark_mode'],
	)
);
?>
<div class="masterstudy-course-player-assignments">
	<?php if ( 'draft' !== $current_template ) { ?>
		<div class="masterstudy-course-player-assignments__status masterstudy-course-player-assignments__status_<?php echo esc_attr( $current_template ); ?>">
			<?php if ( 'reviewing' === $current_template ) { ?>
				<img src="<?php echo esc_url( STM_LMS_URL . '/assets/icons/lessons/pending.gif' ); ?>" class="masterstudy-course-player-assignments__status-image">
				<?php
			} else {
				?>
				<div class="masterstudy-course-player-assignments__status-icon"></div>
			<?php } ?>
			<div class="masterstudy-course-player-assignments__status-wrapper">
				<div class="masterstudy-course-player-assignments__status-message">
					<?php echo esc_html( $data['status_messages'][ $current_template ] ); ?>
				</div>
				<?php
				if ( isset( $data['retake']['total'] ) && isset( $data['retake']['attempts'] ) && 'unpassed' === $current_template ) {
					?>
					<div class="masterstudy-course-player-assignments__status-attempts">
						<?php
						printf(
							/* translators: %s: number */
							esc_html__(
								'%1$s from %2$s attempts left.',
								'masterstudy-lms-learning-management-system-pro'
							),
							esc_html( $data['retake']['attempts'] ),
							esc_html( $data['retake']['total'] )
						);
						?>
					</div>
				<?php } ?>
			</div>
			<?php
			if ( $data['retake']['can_attempt'] && 'unpassed' === $current_template ) {
				$query_args = array(
					'start_assignment' => $item_id,
					'course_id'        => $post_id,
				);
				STM_LMS_Templates::show_lms_template(
					'components/button',
					array(
						'id'    => 'masterstudy-course-player-assignments-send-button',
						'title' => __( 'Retake', 'masterstudy-lms-learning-management-system-pro' ),
						'link'  => add_query_arg( $query_args, $data['actual_link'] ),
						'icon'  => '',
						'style' => 'primary',
						'size'  => 'sm',
					)
				);
			}
			?>
		</div>
	<?php } ?>
	<div class="masterstudy-course-player-assignments__task">
		<span class="masterstudy-course-player-assignments__task-button">
			<?php esc_html_e( 'Requirements', 'masterstudy-lms-learning-management-system-pro' ); ?>
		</span>
		<div class="masterstudy-course-player-assignments__task-content">
			<?php echo wp_kses_post( $data['content'] ); ?>
		</div>
	</div>
	<?php if ( 'draft' === $current_template ) { ?>
		<div class="masterstudy-course-player-assignments__edit" data-editor="<?php echo esc_attr( $data['editor_id'] ); ?>">
			<span class="masterstudy-course-player-assignments__edit-title">
				<?php esc_html_e( 'Assignment', 'masterstudy-lms-learning-management-system-pro' ); ?>
			</span>
			<?php
			STM_LMS_Templates::show_lms_template(
				'components/wp-editor',
				array(
					'id'        => $data['editor_id'],
					'content'   => $data['template_data'][ $current_template ]['content'],
					'settings'  => array(
						'quicktags'     => false,
						'media_buttons' => false,
						'textarea_rows' => 13,
					),
					'dark_mode' => $data['dark_mode'],
				)
			);
			?>
		</div>
		<?php
	} elseif ( ! empty( $data['template_data'][ $current_template ]['content'] ) && 'draft' !== $current_template ) {
		?>
		<div class="masterstudy-course-player-assignments__user-answer">
			<span class="masterstudy-course-player-assignments__user-answer-title">
				<?php echo esc_html__( 'Your answer:', 'masterstudy-lms-learning-management-system-pro' ); ?>
			</span>
			<div class="masterstudy-course-player-assignments__user-answer-content">
				<?php echo wp_kses_post( $data['template_data'][ $current_template ]['content'] ); ?>
			</div>
		</div>
	<?php } ?>
	<div class="masterstudy-course-player-assignments__materials">
		<?php
		STM_LMS_Templates::show_lms_template(
			'components/file-upload',
			array(
				'attachments'            => $data['attachments'],
				'allowed_extensions'     => $data['attachments_settings']['allowed_extensions'],
				'files_limit'            => $data['attachments_settings']['files_limit'],
				'allowed_filesize'       => $data['attachments_settings']['allowed_filesize'],
				'allowed_filesize_label' => $data['attachments_settings']['allowed_filesize_label'],
				'readonly'               => $data['file_loader_readonly'][ $current_template ],
				'dark_mode'              => $data['dark_mode'],
			)
		);
		?>
	</div>
	<?php
	if ( ( 'passed' === $current_template || 'unpassed' === $current_template ) && ! empty( $data['template_data'][ $current_template ]['meta']['editor_comment'][0] ) ) {
		STM_LMS_Templates::show_lms_template(
			'course-player/assignments/instructor-comment',
			array(
				'user'    => $data['comment_author'],
				'comment' => $data['template_data'][ $current_template ]['meta']['editor_comment'][0],
			)
		);
	}
	do_action( 'stm_lms_after_assignment' );
	?>
</div>
