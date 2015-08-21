<?php

/**
 * Validate connection to MailPlatform
 */
function mailplatform_options_validate() {

    $result = mailplatform_xmlrequest('lists', 'GetLists', '');

    if (strtolower($result->status) == "success") {
        $_SESSION['mailplatform_success'] = __('Your settings has been saved and the API is valid');
    } else if (strtolower($result->errormessage) == 'either username or token is missing!') {
        $_SESSION['mailplatform_error'] = __('FAILED') . ": " . __('Either XMLPATH, Username or Token is missing!');
    } else {
        $_SESSION['mailplatform_error'] = __('FAILED') . ": " . __('Unable to check user details.');
    }
}

/**
 * Check username for API
 * @return bool
 */
function mailplatform_checkApiUsername() {

    $username = get_option('mailplatform_username');
    $token = get_option('mailplatform_token');
    $path = get_option('mailplatform_xml_path');

    return empty($username) || empty($token) || empty($path) ? false : true;
}

/**
 * Request handler
 *
 * @param $requesttype
 * @param $requestmethod
 * @param $details
 *
 * @return \SimpleXMLElement
 */
function mailplatform_xmlrequest($requesttype, $requestmethod, $details) {

    $username = get_option('mailplatform_username');
    $token = get_option('mailplatform_token');
    $path = get_option('mailplatform_xml_path');

    $xml = "<?xml version='1.0' encoding='UTF-8' ?><xmlrequest>
	<username>{$username}</username>
	<usertoken>{$token}</usertoken>
	<requesttype>{$requesttype}</requesttype>
	<requestmethod>{$requestmethod}</requestmethod>
	<details>{$details}</details>
	</xmlrequest>";

    $ch = curl_init($path);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
    $result = curl_exec($ch);
    curl_close($ch);

    return new SimpleXMLElement($result);
}

/**
 * Change xml to an array
 *
 * @param       $xmlObject
 * @param array $out
 *
 * @return array
 */
function mailplatform_xml2array($xmlObject, $out = array()) {

    foreach ((array)$xmlObject as $index => $node)
        $out[$index] = (is_object($node)) ? mailplatform_xml2array($node) : $node;

    return $out;
}

/**
 * Redirect to url
 *
 * @param $url
 */
function mailplatform_redirect($url) {

    $string = '<script type="text/javascript">';
    $string .= 'window.location = "' . $url . '"';
    $string .= '</script>';
    echo $string;
}

//===> DATABASE <===\\
global $jal_db_version, $tablename;
$jal_db_version = '1.0';

/**
 * Create table
 */
function jal_install() {

    global $wpdb;
    global $jal_db_version;

    $table_name = $wpdb->prefix . 'mailplatform';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		mailplatform_listid mediumint(9),
		title varchar(255),
		short_description varchar(255),
		url varchar(255),
		message_success varchar(255),
		message_user varchar(255),
		message_error varchar(255),
		buttontext varchar(255),
		redirect_onsuccess tinyint(1),
		input_label tinyint(1),
		show_title tinyint(1),
		show_description tinyint(1),
		autoresponder tinyint(1),
		custom_fields text,
		field_types text,
		field_titles text,
		field_position text,
		UNIQUE KEY id (id)
	) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    add_option('jal_db_version', $jal_db_version);
}

/**
 * Remove DB on deactivation
 */
function jal_uninstall() {

    global $wpdb;
    $table_name = $wpdb->prefix . 'mailplatform';
    $wpdb->query("DROP TABLE {$table_name}");
}

/**
 * @param array $form
 *
 * @return false|int|string
 */
function mailplatform_save_data($form = array()) {

    global $wpdb;

    $table_name = $wpdb->prefix . 'mailplatform';

    $rtrn = "";

    if ($form['id'] != 0) {
        $rtrn = $wpdb->update(
            $table_name,
            array(
                'title'              => $form['title'],
                'short_description'  => $form['short_description'],
                'url'                => $form['url'],
                'message_success'    => $form['message_success'],
                'message_user'       => $form['message_user'],
                'message_error'      => $form['message_error'],
                'buttontext'         => $form['buttontext'],
                'redirect_onsuccess' => $form['redirect_onsuccess'],
                'input_label'        => $form['input_label'],
                'show_title'         => $form['show_title'],
                'show_description'   => $form['show_description'],
                'autoresponder'      => $form['autoresponder'],
                'custom_fields'      => $form['custom_fields'],
                'field_types'        => $form['field_types'],
                'field_titles'       => $form['field_titles'],
                'field_position'     => $form['field_position']
            ),
            array('id' => $form['id'])
        );
    } else {
        $rtrn = $wpdb->insert(
            $table_name,
            array(
                'mailplatform_listid' => $form['mailplatform_listid'],
                'title'               => $form['title'],
                'short_description'   => $form['short_description'],
                'url'                 => $form['url'],
                'message_success'     => $form['message_success'],
                'message_user'        => $form['message_user'],
                'message_error'       => $form['message_error'],
                'buttontext'          => $form['buttontext'],
                'redirect_onsuccess'  => $form['redirect_onsuccess'],
                'input_label'         => $form['input_label'],
                'show_title'          => $form['show_title'],
                'show_description'    => $form['show_description'],
                'autoresponder'       => $form['autoresponder'],
                'custom_fields'       => $form['custom_fields'],
                'field_types'         => $form['field_types'],
                'field_titles'        => $form['field_titles'],
                'field_position'      => $form['field_position']
            )
        );
    }

    return $rtrn;
}

function mailplatform_runThrough($mylink) {

    $mylink->custom_fields = json_decode($mylink->custom_fields);
    $mylink->field_types = json_decode($mylink->field_types);
    $mylink->field_titles = json_decode($mylink->field_titles);
    $mylink->field_position = json_decode($mylink->field_position);

    return $mylink;

}

function mailplatform_get_data($mailplatform_listid = null) {

    global $wpdb;
    $table_name = $wpdb->prefix . 'mailplatform';

    $sql = "SELECT * FROM $table_name";

    if ($mailplatform_listid !== null) {
        $sql .= " WHERE mailplatform_listid = {$mailplatform_listid}";
        $mylink = $wpdb->get_row($sql);
        $_SESSION['mailplatform_id'] = $mylink->id;
    } else {
        $mylink = $wpdb->get_results($sql);
    }

    if (!empty($mylink)) {

        if ($mailplatform_listid !== null) {
            mailplatform_runThrough($mylink);
        } else {
            foreach ($mylink as $item) {
                $item = mailplatform_runThrough($item);
            }
        }
    }

    return $mylink;
}

function mailplatform_form_widget($mailplatform_listid, $data, $type = null, $errors = null, $values = null) {

    ?>
    <div id="mailplatform-<?php echo $mailplatform_listid ?>-<?php echo $data->id ?>-<?php echo $type ?>"
         class="mailplatform_subscribe_widget">

        <form method="post">
            <?php
            if (count($data->custom_fields) > 0) {
                foreach ($data->field_position as $id => $num) {
                    foreach ($data->custom_fields as $key => $val) {
                        if ($key == $id) {
                            $label = $data->field_titles->$key;
                            ?>
                            <p>
                                <?php if ($data->input_label == 1) { ?><label class="mailplatform_label"
                                                                              for="mailplatform-<?php echo $mailplatform_listid; ?>-<?php echo $data->id; ?>-<?php echo $val; ?>"><?php echo $label ?></label><?php } ?>
                                <input <?php echo $data->input_label == 0 ? "placeholder='{$label}'" : "" ?>
                                    class="mailplatform_input <?php echo !empty($errors[$val]) ? "mailplatform_input_error" : ""; ?>" <?php echo !empty($values[$val]) ? "value='{$values[$val]}'" : ''; ?>
                                    type="<?php echo $data->field_types->$key; ?>"
                                    id="mailplatform-<?php echo $mailplatform_listid; ?>-<?php echo $data->id; ?>-<?php echo $val; ?>"
                                    name="customfields[<?php echo $data->field_types->$key; ?>][<?php echo $val; ?>]">
                                <?php echo !empty($errors[$val]) ? "<small class='error-text'>{$errors[$val]}</small>" : ""; ?>
                            </p>
                            <?php
                        }
                    }
                }
            }
            ?>
            <p>
                <input
                    id="mailplatform-submit-<?php echo $mailplatform_listid ?>-<?php echo $data->id ?>-<?php echo $type ?>"
                    class="mailplatform_submit" type="submit"
                    name="submit_<?php echo $mailplatform_listid ?>_<?php echo $data->id ?>_<?php echo $type ?>"
                    value="<?php echo stripslashes($data->buttontext); ?>">
            </p>
        </form>
    </div>
    <?php
}

function mailplatform_addToDetails($id, $val) {

    return "<item><fieldid>{$id}</fieldid><value>{$val}</value></item>";
}

function mailplatform_WidgetAndShortcode($mailplatform_listid, $type) {

    $data = mailplatform_get_data($mailplatform_listid);

    if ($data->show_title)
        echo $args['before_title'] . $data->title . $args['after_title'];

    if ($data->show_description)
        echo '<p class="mailplatform_description">' . $data->short_description . '</p>';

    if (isset($_POST['submit_' . $mailplatform_listid . '_' . $data->id . '_' . $type])) {

        $errors = array();
        $values = array();

        if (isset($_SESSION['errors'])) {
            $errors = $_SESSION['errors'];
            unset($_SESSION['errors']);
        }

        $customfields = $_POST['customfields'];

        foreach ($customfields as $type => $arr) {
            foreach ($arr as $id => $val) {
                if ($id == 'emailaddress') {
                    $values['emailaddress'] = esc_html(sanitize_email($val));
                    if (!isset($values['emailaddress'])) $errors['emailaddress'] = __('This field is required', 'mailplatform');
                    if (!filter_var($values['emailaddress'], FILTER_VALIDATE_EMAIL)) $errors['emailaddress'] = __('This is not an email', 'mailplatform');
                    $details = "<emailaddress>" . $values['emailaddress'] . "</emailaddress>";
                }
            }
        }

        $details .= "<mailinglist>{$mailplatform_listid}</mailinglist>";
        $details .= "<add_to_autoresponders>{$data->autoresponder}</add_to_autoresponders>";

        if (count($_POST['customfields']) > 1) {
            $details .= "<customfields>";

            foreach ($customfields as $type => $arr) {

                foreach ($arr as $id => $val) {
                    if ($id != 'emailaddress') {
                        $values[$id] = esc_html(sanitize_text_field($val));
                        if (!empty($val)) {
                            switch ($type) {
                                case 'date':
                                    $date = DateTime::createFromFormat("Y-m-d", esc_html($val));
                                    $val = "<yy>{$date->format('Y')}</yy><mm>{$date->format('m')}</mm><dd>{$date->format('d')}</dd>";
                                    $details .= mailplatform_addToDetails($id, $val);
                                    break;
                                default:
                                    $details .= mailplatform_addToDetails($id, esc_html($val));
                                    break;
                            }
                        } else {
                            $errors[$id] = __('This field is required', 'mailplatform');
                        }
                    }
                }
            }

            $details .= "</customfields>";
        }

        if (count($errors) > 0) {

            mailplatform_form_widget($mailplatform_listid, $data, $type, $errors, $values);

        } else {
            $result = mailplatform_xmlrequest('subscribers', 'AddSubscriberToList', $details);
            if ($result->status == "SUCCESS") {
                echo "<p class='mailplatform_message mailplatform_success'>" . $data->message_success . "</p>";

                if ($data->redirect_onsuccess) {
                    mailplatform_redirect($data->url);
                }
            } else {

                if (strpos(strtolower($result->errormessage), 'subscriber already exists with id') !== false) {
                    echo "<p class='mailplatform_message mailplatform_error'>" . $data->message_user . "</p>";
                } else {
                    echo "<p class='mailplatform_message mailplatform_error'>" . $data->message_error . "</p>";
                }

                mailplatform_form_widget($mailplatform_listid, $data, $type);
            }
        }
    } else {
        mailplatform_form_widget($mailplatform_listid, $data, $type);
    }

}