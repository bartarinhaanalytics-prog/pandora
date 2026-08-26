<?php
/**
 * ناوبری کناری حساب کاربری.
 * query var 'aramesh_account_active' = کلید فعال.
 *
 * @package Aramesh
 */

$active = get_query_var( 'aramesh_account_active' );
$items  = array(
	'dashboard'  => array( __( 'داشبورد', 'aramesh' ), aramesh_page_url( 'account' ), 'layers' ),
	'my_courses' => array( __( 'دوره‌های من', 'aramesh' ), aramesh_page_url( 'my_courses' ), 'video' ),
	'profile'    => array( __( 'ویرایش پروفایل', 'aramesh' ), get_edit_profile_url(), 'users' ),
	'support'    => array( __( 'پشتیبانی', 'aramesh' ), aramesh_page_url( 'contact' ), 'headset' ),
);
?>
<div class="card-soft p-3">
	<nav class="account-nav">
		<?php foreach ( $items as $key => $item ) : ?>
			<a href="<?php echo esc_url( $item[1] ); ?>" class="<?php echo $active === $key ? 'is-active' : ''; ?>">
				<?php echo aramesh_icon( $item[2], 18 ); ?>
				<span class="ms-1"><?php echo esc_html( $item[0] ); ?></span>
			</a>
		<?php endforeach; ?>
		<a href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>" class="text-secondary">
			<?php echo aramesh_icon( 'arrow-left', 18 ); ?>
			<span class="ms-1"><?php esc_html_e( 'خروج', 'aramesh' ); ?></span>
		</a>
	</nav>
</div>
