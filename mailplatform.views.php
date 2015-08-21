<?php

require_once(mailplatform__PLUGIN_DIR . '_inc/functions.php');
require_once(mailplatform__PLUGIN_DIR . 'class.list-table.php');

// Required jQuery UI for sortable

// JS for admin

$mailplatform_listid = isset($_GET['id']) ? (int)esc_html(intval($_GET['id'])) : null;

if (isset($_POST['submit']) && $mailplatform_listid !== null) {
    $form['mailplatform_listid'] = $mailplatform_listid;

    $form['custom_fields'] = (sanitize_text_field(json_encode($_POST['custom_fields_active'])));
    $form['field_types'] = (sanitize_text_field(json_encode($_POST['custom_fields_type'])));
    $form['field_titles'] = (sanitize_text_field(json_encode($_POST['custom_fields_title'])));
    $form['field_position'] = (sanitize_text_field(json_encode($_POST['field_position'])));
    $form['input_label'] = esc_html(sanitize_text_field($_POST['input_label']));

    $form['title'] = esc_html(sanitize_text_field(!empty($_POST['title']) ? $_POST['title'] : null));
    $form['buttontext'] = esc_html(sanitize_text_field(!empty($_POST['buttontext']) ? $_POST['buttontext'] : null));
    $form['short_description'] = esc_html(sanitize_text_field(!empty($_POST['short_description']) ? $_POST['short_description'] : null));
    $form['url'] = esc_html(sanitize_text_field(!empty($_POST['url']) ? $_POST['url'] : null));
    $form['message_success'] = esc_html(sanitize_text_field(!empty($_POST['message_success']) ? $_POST['message_success'] : null));
    $form['message_error'] = esc_html(sanitize_text_field(!empty($_POST['message_error']) ? $_POST['message_error'] : null));
    $form['message_user'] = esc_html(sanitize_text_field(!empty($_POST['message_user']) ? $_POST['message_user'] : null));
    $form['redirect_onsuccess'] = esc_html(sanitize_text_field(isset($_POST['redirect_onsuccess']) ? true : false));
    $form['show_title'] = esc_html(sanitize_text_field(isset($_POST['show_title']) ? true : false));
    $form['show_description'] = esc_html(sanitize_text_field(isset($_POST['show_description']) ? true : false));
    $form['autoresponder'] = esc_html(sanitize_text_field($_POST['autoresponder']));

    $form['id'] = (int)intval(sanitize_text_field($_POST['id']));

    $rtrn = mailplatform_save_data($form);

    $success_text = __('The subscription list has been updated');

}

$path = mailplatform__PLUGIN_DIR . 'wraps/';
$has_backlink = false;
$type = esc_html($_GET['type']);

if (isset($_GET['settings-updated'])) {
    mailplatform_options_validate();
}

$error_text = isset($_SESSION['mailplatform_error']) ? $_SESSION['mailplatform_error'] : null;
$success_text = isset($_SESSION['mailplatform_success']) ? $_SESSION['mailplatform_success'] : $success_text;
unset($_SESSION['mailplatform_error']);
unset($_SESSION['mailplatform_success']);

if (!mailplatform_checkApiUsername()) {
    $error_text = __('Either Username or Token is missing!', 'mailplatform');
    $type = "settings";
}

switch ($type) {
    case 'edit':
        if ($mailplatform_listid == null) {
            mailplatform_redirect();
        }

        $result = mailplatform_xmlrequest('lists', 'GetLists', "<listids>{$mailplatform_listid}</listids>");
        $list = null;

        foreach ($result->data->item as $item) {
            if ($mailplatform_listid == $item->listid) {
                $list = $item;
            }
        }

        $fields = mailplatform_xmlrequest('lists', 'getcustomfields', "<listids>{$mailplatform_listid}</listids>");
        $data = mailplatform_get_data($mailplatform_listid);

        $args = array(
            'sort_order'   => 'asc',
            'sort_column'  => 'post_title',
            'hierarchical' => 1,
            'exclude'      => '',
            'include'      => '',
            'meta_key'     => '',
            'meta_value'   => '',
            'authors'      => '',
            'child_of'     => 0,
            'parent'       => -1,
            'exclude_tree' => '',
            'number'       => '',
            'offset'       => 0,
            'post_type'    => 'page',
            'post_status'  => 'publish'
        );
        $pages = get_pages($args);

        if (!empty($data)) {
            $sidebarBox = '<div class="sidebar-box"><div class="sidebar-header">' . __('Shortcode') . '</div>
			<div class="sidebar-content">
				<pre>[mailplatform listid="' . $mailplatform_listid . '"]</pre>
			</div>
		</div>';
        }

        $include_view = "{$path}edit.php";
        $view_title = __('Editing : ' . $list->name, 'mailplatform');
        $has_backlink = true;
        break;
    case 'delete':
        $include_view = "{$path}delete.php";
        break;
    case 'settings':
        $include_view = "{$path}settings.php";
        $view_title = __('Settings', 'mailplatform');
        break;
    default:
        $view_title = __('List Overview', 'mailplatform');
        $include_view = "{$path}list.php";
        break;
}

?>
<div class="wrap">
    <h2><?php echo $view_title ?> <?php if ($has_backlink) { ?><a href="?page=mailplatform-list-options"
                                                                  class="add-new-h2"><?php _e('Back to list', 'mailplatform') ?></a><?php } ?>
    </h2>

    <?php echo isset($error_text) ? "<div class='mp_error mp_message'><h3><strong>" . __('Error', 'mailplatform') . "</strong> <small>{$error_text}</small></h3></div>" : ""; ?>
    <?php echo isset($success_text) ? "<div class='mp_success mp_message'><h3><strong>" . __('Success', 'mailplatform') . "</strong> <small>{$success_text}</small></h3></div>" : ""; ?>

    <div class="row">
        <section class="col-md-8">
            <?php require_once($include_view); ?>
        </section>
        <aside class="col-md-4">

            <?php echo $sidebarBox; ?>

            <div class="sidebar-box">
                <div class="sidebar-header">
                    <img src="<?php echo plugins_url('content/mailplatform-logo.png', __FILE__) ?>" alt="">
                </div>
                <div class="sidebar-content">
                    <?php echo __('<p>MailPlatform is a healthy Danish enterprise that helps over 2,000 Danish businesses with their send-outs of newsletters and SMS. </p>

<p>Together with my team of 16 skilled colleagues it is my responsibility to ensure that your experience with MailPlatform is a great one. We are there for you all the way through.
Today MailPlatform is Denmark’s only certified provider of email marketing systems. This is of great importance when it comes to delivering your newsletters.
We believe that good service, combined with continuous innovation, is the foundation for a lengthy cooperation. We look forward to helping your business – regardless of size or email volume.
We help your business get off to a good start. It doesn’t cost anything – it’s our investment in happy and satisfied customers.</p>', 'mailplatform') ?>
                </div>
            </div>

            <div class="sidebar-box sidebar-dark">
                <div class="sidebar-header">
                    <h2><?php echo __('Looking for help?', 'mailplatform') ?></h2>
                </div>
                <div class="sidebar-content">
                    <?php echo __('You can always contact us at <a href="mailto:support@mailplatform.net">support@mailplatform.net</a> or you can call us:<br>Monday – Friday between 8.30 am and 3.30 pm (GMT +1) on +44 800 0 488 244 or +44 330 8 080 430', 'mailplatform') ?>
                </div>
            </div>
        </aside>
    </div>
</div>