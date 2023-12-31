<?php
/**
 * @var $course_id
 * @var $item_id
 */

stm_lms_register_script( 'buy-button', array( 'jquery.cookie' ) );
stm_lms_register_style( 'buy-button-mixed' );

$item_id                 = ( ! empty( $item_id ) ) ? $item_id : '';
$has_course              = STM_LMS_User::has_course_access( $course_id, $item_id, false );
$course_price            = STM_LMS_Course::get_course_price( $course_id );
$coming_soon_preordering = get_post_meta( $course_id, 'coming_soon_preordering', true );
$coming_soon_show_price  = get_post_meta( $course_id, 'coming_soon_show_course_price', true );

$is_course_coming_soon = false;
if ( method_exists( 'STM_LMS_Helpers', 'masterstudy_lms_is_course_coming_soon' ) ) {
	$is_course_coming_soon = STM_LMS_Helpers::masterstudy_lms_is_course_coming_soon( $course_id );
}

if ( $is_course_coming_soon && ! $coming_soon_show_price ) {
	return;
}

if ( $is_course_coming_soon && ! $coming_soon_preordering ) {
	?>
	<a href="#" class="masterstudy-coming-soon-disabled-btn"><?php esc_html_e( 'Coming soon', 'masterstudy-lms-learning-management-system-pro' ); ?></a>
	<?php
	return;
}

if ( isset( $has_access ) ) {
	$has_course = $has_access;
}

$is_prerequisite_passed = true;

if ( class_exists( 'STM_LMS_Prerequisites' ) ) {
	$is_prerequisite_passed = STM_LMS_Prerequisites::is_prerequisite( true, $course_id );
}

do_action( 'stm_lms_before_button_mixed', $course_id );

if ( apply_filters( 'stm_lms_before_button_stop', false, $course_id ) && false === $has_course ) {
	return false;
}

$is_affiliate = STM_LMS_Courses_Pro::is_external_course( $course_id );
$not_salebale = get_post_meta( $course_id, 'not_single_sale', true );


if ( ! $is_affiliate ) :
	?>

	<div class="stm-lms-buy-buttons stm-lms-buy-buttons-mixed stm-lms-buy-buttons-mixed-pro">
		<?php if ( ( $has_course || ( empty( $course_price ) && ! $not_salebale ) ) && $is_prerequisite_passed ) : ?>

			<?php
			$user = STM_LMS_User::get_current_user();
			if ( empty( $user['id'] ) ) :
				?>
				<?php
				stm_lms_register_style( 'login' );
				stm_lms_register_style( 'register' );
				enqueue_login_script();
				enqueue_register_script();
				?>

				<a href="#" class="btn btn-default" data-target=".stm-lms-modal-login" data-lms-modal="login">
					<span><?php esc_html_e( 'Enroll course', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
				</a>
				<?php
			else :
				$user_id        = $user['id'];
				$course         = STM_LMS_Helpers::simplify_db_array(
					stm_lms_get_user_course(
						$user_id,
						$course_id,
						array(
							'current_lesson_id',
							'progress_percent',
						)
					)
				);
				$current_lesson = ( ! empty( $course['current_lesson_id'] ) ) ? $course['current_lesson_id'] : '0';
				$progress       = ( ! empty( $course['progress_percent'] ) ) ? intval( $course['progress_percent'] ) : 0;
				$lesson_url     = STM_LMS_Course::item_url( $course_id, $current_lesson );
				$btn_label      = esc_html__( 'Start course', 'masterstudy-lms-learning-management-system-pro' );
				$course_classes = 'btn btn-default start-course';
				if ( $progress > 0 ) {
					$btn_label = esc_html__( 'Continue', 'masterstudy-lms-learning-management-system-pro' );
				} elseif ( $is_course_coming_soon && $coming_soon_preordering && 0 <= $course_price ) {
					$btn_label      = esc_html__( 'Coming soon', 'masterstudy-lms-learning-management-system-pro' );
					$course_classes = $course_classes . ' disabled';
				}
				?>
				<a href="<?php echo esc_url( $lesson_url ); ?>" class="<?php echo esc_attr( $course_classes ); ?>">
				<span><?php echo esc_html( sanitize_text_field( $btn_label ) ); ?></span>
				</a>

			<?php endif; ?>

			<?php
		else :
			$price             = get_post_meta( $course_id, 'price', true );
			$sale_price        = STM_LMS_Course::get_sale_price( $course_id );
			$not_in_membership = get_post_meta( $course_id, 'not_membership', true );
			$btn_class         = array( 'btn btn-default' );

			if ( empty( $price ) && ! empty( $sale_price ) ) {
				$price      = $sale_price;
				$sale_price = '';
			}

			if ( ! empty( $price ) && ! empty( $sale_price ) ) {
				$tmp_price  = $sale_price;
				$sale_price = $price;
				$price      = $tmp_price;
			}

			if ( $not_salebale ) {
				$price      = '';
				$sale_price = '';
			}

			$btn_class[] = 'btn_big heading_font';

			if ( is_user_logged_in() ) {
				$attributes = array();
				if ( ! $not_salebale ) {
					$attributes[] = 'data-buy-course="' . intval( $course_id ) . '"';
				}
			} else {
				stm_lms_register_style( 'login' );
				stm_lms_register_style( 'register' );
				enqueue_login_script();
				enqueue_register_script();
				$attributes = array(
					'data-target=".stm-lms-modal-login"',
					'data-lms-modal="login"',
				);
			}

			$subscription_enabled = ( empty( $not_in_membership ) && STM_LMS_Subscriptions::subscription_enabled() );
			if ( $subscription_enabled ) {
				$plans_courses = STM_LMS_Course::course_in_plan( $course_id );
			}

			$dropdown_enabled = ! empty( $plans_courses );

			if ( empty( $plans_courses ) ) {
				$dropdown_enabled = is_user_logged_in() && class_exists( 'STM_LMS_Point_System' );
			}

			$mixed_classes   = array( 'stm_lms_mixed_button' );
			$mixed_classes[] = ( $dropdown_enabled ) ? 'subscription_enabled' : 'subscription_disabled';

			$show_buttons = apply_filters( 'stm_lms_pro_show_button', true, $course_id );
			if ( $show_buttons ) :
				?>
			<div class="<?php echo esc_attr( implode( ' ', $mixed_classes ) ); ?>">
				<div class="buy-button <?php echo esc_attr( implode( ' ', $btn_class ) ); ?>" 
						<?php
						if ( ! $dropdown_enabled ) {
							echo wp_kses_post( implode( ' ', apply_filters( 'stm_lms_buy_button_auth', $attributes, $course_id ) ) );
						}
						?>
				>

					<span>
						<?php esc_html_e( 'Get course', 'masterstudy-lms-learning-management-system-pro' ); ?>
					</span>

					<?php if ( ! empty( $price ) || ! empty( $sale_price ) ) : ?>
						<div class="btn-prices btn-prices-price">

							<?php if ( ! empty( $sale_price ) ) : ?>
								<label class="sale_price" title="<?php echo esc_attr( STM_LMS_Helpers::display_price( $sale_price ) ); ?>"><?php echo wp_kses_post( STM_LMS_Helpers::display_price( $sale_price ) ); ?></label>
							<?php endif; ?>

							<?php if ( ! empty( $price ) ) : ?>
								<label class="price" title="<?php echo esc_attr( STM_LMS_Helpers::display_price( $price ) ); ?>"><?php echo wp_kses_post( STM_LMS_Helpers::display_price( $price ) ); ?></label>
							<?php endif; ?>

						</div>
					<?php endif; ?>

				</div>

				<div class="stm_lms_mixed_button__list">
					<?php
					if ( $dropdown_enabled ) :
						stm_lms_register_style( 'membership' );
						$subs = STM_LMS_Subscriptions::user_subscription_levels();

						if ( ! $not_salebale ) :
							?>
							<a class="stm_lms_mixed_button__single" href="#" <?php echo wp_kses_post( implode( ' ', apply_filters( 'stm_lms_buy_button_auth', $attributes, $course_id ) ) ); ?>>
								<span><?php esc_html_e( 'One Time Payment', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
							</a>
							<?php
						endif;

						if ( $subscription_enabled && ! empty( $plans_courses ) ) :
							$plans_course_ids = wp_list_pluck( $plans_courses, 'id' );
							$plans_have_quota = false;
							$needs_approval   = false;

							foreach ( $subs as $sub ) {
								if ( ! in_array( $sub->ID, $plans_course_ids ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
									continue;
								}

								if ( $sub->course_number > 0 ) {
									$plans_have_quota = true;
									$user_approval    = get_user_meta( get_current_user_id(), 'pmpro_approval_' . $sub->ID, true );

									// phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
									if ( ! empty( $user_approval['status'] ) && in_array( $user_approval['status'], array( 'pending', 'denied' ) ) ) {
										$needs_approval = true;
									}
								}
							}

							if ( $plans_have_quota ) :
								$subs_info = array();

								foreach ( $subs as $sub ) {
									if ( ! in_array( $sub->ID, $plans_course_ids ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
										continue;
									}

									$subs_info[] = array(
										'id'            => $sub->subscription_id,
										'course_id'     => get_the_ID(),
										'name'          => $sub->name,
										'course_number' => $sub->course_number,
										'used_quotas'   => $sub->used_quotas,
										'quotas_left'   => $sub->quotas_left,
									);
								}
								?>
								<button type="button"
										data-lms-params='<?php echo wp_json_encode( $subs_info ); ?>'
										class=""
										data-target=".stm-lms-use-subscription"
										data-lms-modal="use_subscription"
										<?php
										if ( $needs_approval ) {
											echo 'disabled="disabled"';}
										?>
										>
									<span><?php esc_html_e( 'Enroll with Membership', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
									<?php if ( $needs_approval ) : ?>
										<small><?php esc_html_e( 'Your membership account is not approved!', 'masterstudy-lms-learning-management-system-pro' ); ?></small>
									<?php endif; ?>
								</button>

								<?php
							else :
								$buy_url   = STM_LMS_Subscriptions::level_url();
								$buy_label = esc_html__( 'Enroll with Membership', 'masterstudy-lms-learning-management-system-pro' );

								$plans = array(
									$buy_url => $buy_label,
								);

								if ( ! empty( $plans_courses ) ) {
									$plans = array();

									foreach ( $plans_courses as $plan_course ) {
										$plan_course_limit = get_option( "stm_lms_course_number_{$plan_course->id}", 0 );

										if ( empty( $plan_course_limit ) ) {
											continue;
										}

										stm_lms_register_script( 'buy/plan_cookie', array( 'jquery.cookie' ), true );

										$buy_url   = add_query_arg( 'level', $plan_course->id, STM_LMS_Subscriptions::checkout_url() );
										$buy_label = sprintf(
											/* translators: %s: plan name */
											esc_html__( 'Available in "%s" plan', 'masterstudy-lms-learning-management-system-pro' ),
											$plan_course->name
										);

										$plans[ $buy_url ] = $buy_label;
									}
								}

								foreach ( $plans as $plan_url => $plan_label ) :
									?>
									<a href="<?php echo esc_url( $plan_url ); ?>"
											class="btn btn-default btn-subscription btn-outline btn-save-checkpoint"
											data-course-id="<?php echo esc_attr( $course_id ); ?>">
										<span><?php echo esc_html( $plan_label ); ?></span>
									</a>
									<?php
								endforeach;
							endif;
						endif;
					endif;

					do_action( 'stm_lms_after_mixed_button_list', $course_id );
					?>

				</div>
			</div>
				<?php
		else :
			do_action( 'stm_lms_pro_instead_buttons', $course_id );
			endif;
		endif;

		do_action( 'stm_lms_buy_button_end', $course_id );
		?>

	</div>

	<?php
endif;
