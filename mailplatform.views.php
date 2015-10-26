<?php

require_once( mailplatform__PLUGIN_DIR . '_inc/functions.php' );
require_once( mailplatform__PLUGIN_DIR . 'class.list-table.php' );

// Required jQuery UI for sortable

// JS for admin
$type = esc_html( $_GET['type'] );

$path         = mailplatform__PLUGIN_DIR . 'wraps/';
$has_backlink = false;

if ( $type !== 'feeds' ) {

	$mailplatform_listid = isset( $_GET['id'] ) ? (int) esc_html( intval( $_GET['id'] ) ) : null;

	if ( isset( $_POST['submit'] ) && $mailplatform_listid !== null ) {
		$form['mailplatform_listid'] = $mailplatform_listid;

		$form['custom_fields']  = ( sanitize_text_field( json_encode( $_POST['custom_fields_active'] ) ) );
		$form['field_types']    = ( sanitize_text_field( json_encode( $_POST['custom_fields_type'] ) ) );
		$form['field_titles']   = ( sanitize_text_field( json_encode( $_POST['custom_fields_title'] ) ) );
		$form['field_position'] = ( sanitize_text_field( json_encode( $_POST['field_position'] ) ) );
		$form['input_label']    = esc_html( sanitize_text_field( $_POST['input_label'] ) );

		$form['title']              = esc_html( sanitize_text_field( ! empty( $_POST['title'] ) ? $_POST['title'] : null ) );
		$form['buttontext']         = esc_html( sanitize_text_field( ! empty( $_POST['buttontext'] ) ? $_POST['buttontext'] : null ) );
		$form['short_description']  = esc_html( sanitize_text_field( ! empty( $_POST['short_description'] ) ? $_POST['short_description'] : null ) );
		$form['url']                = esc_html( sanitize_text_field( ! empty( $_POST['url'] ) ? $_POST['url'] : null ) );
		$form['message_success']    = esc_html( sanitize_text_field( ! empty( $_POST['message_success'] ) ? $_POST['message_success'] : null ) );
		$form['message_error']      = esc_html( sanitize_text_field( ! empty( $_POST['message_error'] ) ? $_POST['message_error'] : null ) );
		$form['message_user']       = esc_html( sanitize_text_field( ! empty( $_POST['message_user'] ) ? $_POST['message_user'] : null ) );
		$form['redirect_onsuccess'] = esc_html( sanitize_text_field( isset( $_POST['redirect_onsuccess'] ) ? true : false ) );
		$form['show_title']         = esc_html( sanitize_text_field( isset( $_POST['show_title'] ) ? true : false ) );
		$form['show_description']   = esc_html( sanitize_text_field( isset( $_POST['show_description'] ) ? true : false ) );
		$form['autoresponder']      = esc_html( sanitize_text_field( $_POST['autoresponder'] ) );

		$form['id'] = (int) intval( sanitize_text_field( $_POST['id'] ) );

		$rtrn = mailplatform_save_data( $form );

		$success_text = __( 'The subscription list has been updated' );

	}

	if ( isset( $_GET['settings-updated'] ) ) {
		mailplatform_options_validate();
	}

	$error_text   = isset( $_SESSION['mailplatform_error'] ) ? $_SESSION['mailplatform_error'] : null;
	$success_text = isset( $_SESSION['mailplatform_success'] ) ? $_SESSION['mailplatform_success'] : $success_text;
	unset( $_SESSION['mailplatform_error'] );
	unset( $_SESSION['mailplatform_success'] );

	if ( ! mailplatform_checkApiUsername() ) {
		$error_text = __( 'Either Username or Token is missing!', 'mailplatform' );
		$type       = "settings";
	}
}

switch ( $type ) {
	case 'edit':
		if ( $mailplatform_listid == null ) {
			mailplatform_redirect();
		}

		$result = mailplatform_xmlrequest( 'lists', 'GetLists', "<listids>{$mailplatform_listid}</listids>" );
		$list   = null;

		foreach ( $result->data->item as $item ) {
			if ( $mailplatform_listid == $item->listid ) {
				$list = $item;
			}
		}

		$fields = mailplatform_xmlrequest( 'lists', 'getcustomfields', "<listids>{$mailplatform_listid}</listids>" );
		$data   = mailplatform_get_data( $mailplatform_listid );

		$args  = array(
			'sort_order'   => 'asc',
			'sort_column'  => 'post_title',
			'hierarchical' => 1,
			'exclude'      => '',
			'include'      => '',
			'meta_key'     => '',
			'meta_value'   => '',
			'authors'      => '',
			'child_of'     => 0,
			'parent'       => - 1,
			'exclude_tree' => '',
			'number'       => '',
			'offset'       => 0,
			'post_type'    => 'page',
			'post_status'  => 'publish'
		);
		$pages = get_pages( $args );

		if ( ! empty( $data ) ) {
			$sidebarBox = '<div class="sidebar-box"><div class="sidebar-header">' . __( 'Shortcode' ) . '</div>
			<div class="sidebar-content">
				<pre>[mailplatform listid="' . $mailplatform_listid . '"]</pre>
			</div>
		</div>';
		}

		$include_view = "{$path}edit.php";
		$view_title   = __( 'Editing : ' . $list->name, 'mailplatform' );
		$has_backlink = true;
		break;
	case 'delete':
		$include_view = "{$path}delete.php";
		break;
	case 'settings':
		$include_view = "{$path}settings.php";
		$view_title   = __( 'Settings', 'mailplatform' );
		break;
	case 'feeds':
		$include_view = "{$path}feed-settings.php";
		$view_title   = __( 'XML Feed Settings', 'mailplatform' );
		break;
	default:
		$view_title   = __( 'List Overview', 'mailplatform' );
		$include_view = "{$path}list.php";
		break;
}

?>
<div class="wrap" id="mailplatform_wrapper">
	<h2><?php echo $view_title ?> <?php if ( $has_backlink ) { ?><a href="?page=mailplatform-list-options"
	                                                                class="add-new-h2"><?php _e( 'Back to list', 'mailplatform' ) ?></a><?php } ?>
	</h2>

	<?php echo isset( $error_text ) ? "<div class='mp_error mp_message'><h3><strong>" . __( 'Error', 'mailplatform' ) . "</strong> <small>{$error_text}</small></h3></div>" : ""; ?>
	<?php echo isset( $success_text ) ? "<div class='mp_success mp_message'><h3><strong>" . __( 'Success', 'mailplatform' ) . "</strong> <small>{$success_text}</small></h3></div>" : ""; ?>

	<div class="row">
		<section class="col-md-8">
			<?php require_once( $include_view ); ?>
		</section>
		<aside class="col-md-4">

			<?php echo $sidebarBox; ?>

			<div class="sidebar-box">
				<div class="sidebar-header">
					<img src="data:image/png;base64,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"/>
				</div>
				<div class="sidebar-content">
					<?php echo __( '<p>MailPlatform is a healthy Danish enterprise that helps over 2,000 Danish businesses with their send-outs of newsletters and SMS. </p>

<p>Together with my team of 16 skilled colleagues it is my responsibility to ensure that your experience with MailPlatform is a great one. We are there for you all the way through.
Today MailPlatform is Denmark’s only certified provider of email marketing systems. This is of great importance when it comes to delivering your newsletters.
We believe that good service, combined with continuous innovation, is the foundation for a lengthy cooperation. We look forward to helping your business – regardless of size or email volume.
We help your business get off to a good start. It doesn’t cost anything – it’s our investment in happy and satisfied customers.</p>', 'mailplatform' ) ?>
				</div>
			</div>

			<div class="sidebar-box sidebar-dark">
				<div class="sidebar-header">
					<h2><?php echo __( 'Looking for help?', 'mailplatform' ) ?></h2>
				</div>
				<div class="sidebar-content">
					<?php echo __( 'You can always contact us at <a href="mailto:support@mailplatform.net">support@mailplatform.net</a> or you can call us:<br>Monday – Friday between 8.30 am and 3.30 pm (GMT +1) on +44 800 0 488 244 or +44 330 8 080 430', 'mailplatform' ) ?>
				</div>
			</div>
		</aside>
	</div>
</div>