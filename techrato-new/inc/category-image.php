<?php
/**
 * A proper image field on the category screens.
 *
 * WordPress has no built-in category image, which is why the picture had to be
 * pasted into the description. This adds a real upload field on the add and
 * edit category forms and stores the attachment ID in term meta, where
 * techrato_term_image() already looks for it.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Term meta key holding the chosen attachment ID.
 */
const TECHRATO_TERM_IMAGE_KEY = 'techrato_term_image_id';

/**
 * The media library scripts are only loaded on the screens that need them.
 */
function techrato_term_image_admin_assets( $hook ) {
	if ( ! in_array( $hook, array( 'edit-tags.php', 'term.php' ), true ) ) {
		return;
	}

	$taxonomy = isset( $_GET['taxonomy'] ) ? sanitize_key( $_GET['taxonomy'] ) : 'post_tag'; // phpcs:ignore WordPress.Security.NonceVerification
	if ( 'category' !== $taxonomy ) {
		return;
	}

	wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'techrato_term_image_admin_assets' );

/**
 * The field markup, shared by the add and edit forms.
 *
 * @param int $attachment_id Currently selected image, 0 when none.
 */
function techrato_term_image_control( $attachment_id = 0 ) {
	$preview = $attachment_id ? wp_get_attachment_image( $attachment_id, 'medium', false, array( 'style' => 'max-width:220px;height:auto;border-radius:8px;' ) ) : '';
	?>
	<div class="techrato-term-image">
		<input type="hidden" name="techrato_term_image_id" class="techrato-term-image-id" value="<?php echo esc_attr( $attachment_id ); ?>">
		<div class="techrato-term-image-preview" style="margin-bottom:8px;"><?php echo $preview; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
		<button type="button" class="button techrato-term-image-pick"><?php esc_html_e( 'انتخاب عکس', 'techrato' ); ?></button>
		<button type="button" class="button-link techrato-term-image-clear" style="margin-inline-start:10px;color:#b32d2e;<?php echo $attachment_id ? '' : 'display:none;'; ?>"><?php esc_html_e( 'حذف عکس', 'techrato' ); ?></button>
		<p class="description"><?php esc_html_e( 'این عکس در صفحه دسته‌بندی، کنار توضیحات نمایش داده می‌شود.', 'techrato' ); ?></p>
	</div>
	<script>
	( function () {
		var wrap = document.currentScript.closest( '.form-field, td' ) || document;
		var box  = wrap.querySelector( '.techrato-term-image' );
		if ( ! box || box.dataset.ready ) {
			return;
		}
		box.dataset.ready = '1';

		var field   = box.querySelector( '.techrato-term-image-id' );
		var preview = box.querySelector( '.techrato-term-image-preview' );
		var clear   = box.querySelector( '.techrato-term-image-clear' );
		var frame;

		box.querySelector( '.techrato-term-image-pick' ).addEventListener( 'click', function () {
			if ( ! frame ) {
				frame = wp.media( {
					title: <?php echo wp_json_encode( __( 'انتخاب عکس دسته‌بندی', 'techrato' ) ); ?>,
					button: { text: <?php echo wp_json_encode( __( 'استفاده از این عکس', 'techrato' ) ); ?> },
					library: { type: 'image' },
					multiple: false
				} );
				frame.on( 'select', function () {
					var img = frame.state().get( 'selection' ).first().toJSON();
					var src = ( img.sizes && img.sizes.medium ) ? img.sizes.medium.url : img.url;
					field.value = img.id;
					preview.innerHTML = '<img src="' + src + '" style="max-width:220px;height:auto;border-radius:8px;" alt="">';
					clear.style.display = '';
				} );
			}
			frame.open();
		} );

		clear.addEventListener( 'click', function () {
			field.value = '';
			preview.innerHTML = '';
			clear.style.display = 'none';
		} );
	} )();
	</script>
	<?php
}

/**
 * Field on the "add new category" form.
 */
function techrato_term_image_add_field() {
	?>
	<div class="form-field">
		<label><?php esc_html_e( 'عکس دسته‌بندی', 'techrato' ); ?></label>
		<?php techrato_term_image_control( 0 ); ?>
	</div>
	<?php
}
add_action( 'category_add_form_fields', 'techrato_term_image_add_field' );

/**
 * Field on the "edit category" form.
 *
 * @param WP_Term $term Term being edited.
 */
function techrato_term_image_edit_field( $term ) {
	$attachment_id = (int) get_term_meta( $term->term_id, TECHRATO_TERM_IMAGE_KEY, true );
	?>
	<tr class="form-field">
		<th scope="row"><label><?php esc_html_e( 'عکس دسته‌بندی', 'techrato' ); ?></label></th>
		<td><?php techrato_term_image_control( $attachment_id ); ?></td>
	</tr>
	<?php
}
add_action( 'category_edit_form_fields', 'techrato_term_image_edit_field' );

/**
 * Store the choice.
 *
 * @param int $term_id Term being saved.
 */
function techrato_term_image_save( $term_id ) {
	if ( ! current_user_can( 'manage_categories' ) ) {
		return;
	}

	// The field is absent on quick-edit and on other forms that post to the
	// same hooks; leaving the stored value alone is the right move there.
	if ( ! isset( $_POST['techrato_term_image_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		return;
	}

	$attachment_id = absint( $_POST['techrato_term_image_id'] ); // phpcs:ignore WordPress.Security.NonceVerification

	if ( $attachment_id && 'attachment' === get_post_type( $attachment_id ) ) {
		update_term_meta( $term_id, TECHRATO_TERM_IMAGE_KEY, $attachment_id );
	} else {
		delete_term_meta( $term_id, TECHRATO_TERM_IMAGE_KEY );
	}
}
add_action( 'created_category', 'techrato_term_image_save' );
add_action( 'edited_category', 'techrato_term_image_save' );

/**
 * Show the image in the category list table, so the admin screen tells you at
 * a glance which categories still need one.
 *
 * @param array $columns Existing columns.
 * @return array
 */
function techrato_term_image_column( $columns ) {
	$with_image = array();
	foreach ( $columns as $key => $label ) {
		if ( 'name' === $key ) {
			$with_image['techrato_image'] = __( 'عکس', 'techrato' );
		}
		$with_image[ $key ] = $label;
	}
	return $with_image;
}
add_filter( 'manage_edit-category_columns', 'techrato_term_image_column' );

/**
 * Render that column.
 *
 * @param string $content Existing cell content.
 * @param string $column  Column key.
 * @param int    $term_id Term ID.
 * @return string
 */
function techrato_term_image_column_content( $content, $column, $term_id ) {
	if ( 'techrato_image' !== $column ) {
		return $content;
	}

	$attachment_id = (int) get_term_meta( $term_id, TECHRATO_TERM_IMAGE_KEY, true );
	if ( ! $attachment_id ) {
		return '<span style="color:#999;">—</span>';
	}

	return wp_get_attachment_image( $attachment_id, array( 48, 48 ), false, array( 'style' => 'border-radius:6px;' ) );
}
add_filter( 'manage_category_custom_column', 'techrato_term_image_column_content', 10, 3 );
