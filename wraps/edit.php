<form action="<?php echo admin_url("admin.php?page=mailplatform-list-options&type=edit&id={$mailplatform_listid}"); ?>"
      method="post">
    <div class="sidebar-box">
        <div class="sidebar-header">
            <h2><?php echo __( 'Visible Fields', 'mailplatform' ) ?></h2>
        </div>
        <div class="sidebar-content">
            <?php settings_fields('mailplatform-db-customfields'); ?>
            <div class="mailplatform-custom-form">
                <input type="hidden" name="mailplatform_listid" value="<?php echo $mailplatform_listid; ?>">
                <input type="hidden" name="title" value="<?php echo $list->name; ?>">
                <input type="hidden" name="id" value="<?php echo $data->id; ?>">

                <div class="mp_input_group">
                    <div>
                        <span class="mp_label"><?php echo __('Display text in', 'mailplatform') ?></span>
					<span class="mp_select">
						<label
                            class="mp_radio"><input <?php echo $data->input_label == 1 || !isset($data->input_label) ? 'checked' : '' ?>
                                type="radio" name="input_label" value="1"><?php echo __('Label', 'mailplatform') ?></label>
						<label
                            class="mp_radio"><input <?php echo $data->input_label == 0 && isset($data->input_label) ? 'checked' : '' ?>
                                type="radio" name="input_label" value="0"><?php echo __('Placeholder', 'mailplatform') ?>
                        </label>
						<label
                            class="mp_radio"><input <?php echo $data->input_label == 3 && isset($data->input_label) ? 'checked' : '' ?>
                                type="radio" name="input_label" value="3"><?php echo __('No labels', 'mailplatform') ?></label>
					</span>
                    </div>
                </div>
                <div class="mp_input_group">
                    <div>
                        <span class="mp_label"><?php echo __('Autoresponder', 'mailplatform') ?></span>
					<span class="mp_select">
						<label
                            class="mp_radio"><input <?php echo $data->autoresponder == 1 || !isset($data) ? 'checked' : '' ?>
                                type="radio" name="autoresponder" value="1"><?php echo __('Activate', 'mailplatform') ?>
                        </label>
						<label
                            class="mp_radio"><input <?php echo $data->autoresponder == 0 && isset($data->autoresponder) ? 'checked' : '' ?>
                                type="radio" name="autoresponder" value="0"><?php echo __('Deactivate', 'mailplatform') ?>
                        </label>
					</span>
                    </div>
                </div>

                <p class="helper"><?php echo __('Click to choose which fields that is visible, choose an input type, the default type is text.', 'mailplatform') ?></p>

                <?php
                $fieldname = 'emailaddress';
                ?>
                <ul class="sortable-list">
                    <li data-position="<?php echo !isset($data) ? 0 : $data->field_position->$fieldname ?>">
                        <div class="mp_input_group mp_checkbox_group mp_field_input">
                            <input id="custom_fields-[<?php echo $fieldname ?>]" type="checkbox"
                                   name="custom_fields_active[<?php echo $fieldname ?>]" checked value="<?php echo $fieldname; ?>">
                            <label class="mp_hover_label">
                                <input type="hidden" value="email" name="custom_fields_type[<?php echo $fieldname ?>]">

                                <div class="handle"><span class="bar"></span><span class="bar"></span><span
                                        class="bar"></span></div>
                                <input type="hidden" class="mp_position" name="field_position[<?php echo $fieldname ?>]"
                                       value="<?php echo !isset($data) ? 0 : $data->field_position->$fieldname ?>"/>
                                <span class="mp_label"><?php echo __('Email Address', 'mailplatform') ?></span>
                            <span class="mp_select">
                                    <input type="text" name="custom_fields_title[<?php echo $fieldname ?>]"
                                           value="<?php echo isset($data->field_titles->$fieldname) ? $data->field_titles->$fieldname : __('Email Address', 'mailplatform') ?>">
                            </span>
                            </label>
                        </div>
                    </li>
                    <?php
                    $i = 1;
                    foreach ($fields->data->item as $field) {
                        $fieldname = strtolower(str_replace([' ', '/'], ['_', '__'], $field->name));
                        $field_type = "text";
                        if ($fieldname == "birth_date") $field_type = "date";

                        if (!empty($data)) {

                            $is_checked = false;

                            if (!empty($data->custom_fields)) {
                                foreach ($data->custom_fields as $el) {
                                    if ($el == $field->fieldid) $is_checked = true;
                                }
                            }
                        } else {
                            $is_checked = false;
                        }
                        ?>
                        <li data-position="<?php echo !isset($data) ? $i : $data->field_position->$fieldname ?>">
                            <div class="mp_input_group mp_checkbox_group mp_field_input">
                                <input id="custom_fields-<?php echo $fieldname ?>" type="checkbox"
                                       name="custom_fields_active[<?php echo $fieldname ?>]" <?php echo $is_checked ? 'checked' : '' ?>
                                       value="<?php echo $field->fieldid; ?>">
                                <label class="mp_hover_label" for="custom_fields-<?php echo $fieldname ?>">
                                    <input type="hidden" value="<?php echo $field_type ?>"
                                           name="custom_fields_type[<?php echo $fieldname ?>]">

                                    <div class="handle"><span class="bar"></span><span class="bar"></span><span
                                            class="bar"></span></div>
                                    <input type="hidden" class="mp_position" name="field_position[<?php echo $fieldname ?>]"
                                           value="<?php echo !isset($data) ? $i : $data->field_position->$fieldname ?>"/>
                                    <span class="mp_label"><?php echo $field->name; ?></span>
                                    <span class="mp_select">
                                        <input type="text" name="custom_fields_title[<?php echo $fieldname ?>]"
                                               value="<?php echo isset($data->field_titles->$fieldname) ? $data->field_titles->$fieldname : $field->name ?>">
                                    </span>
                                </label>
                            </div>
                        </li>
                    <?php
                        $i++;
                    }
                    ?>
                </ul>
            </div>
            <?php
            do_settings_sections('mailplatform-db-customfields');
            submit_button();
            ?>
        </div>
    </div>
    <div class="sidebar-box">
        <div class="sidebar-header">
            <h2><?php echo __('Messages and texts') ?></h2>
        </div>
        <div class="sidebar-content">
            <div class="mp_input_group mp_checkbox_group">
                <input id="mp_show_title_input" type="checkbox"
                       name="show_title" <?php echo $data->show_title ? 'checked' : '' ?> value="true">
                <label for="mp_show_title_input">
                    <span class="mp_label"><?php echo __('List Titel', 'mailplatform') ?></span>
					<span class="mp_select">
						<input type="text" name="title"
                               value="<?php echo !empty($data) ? stripslashes($data->title) : __('New List', 'mailplatform') ?>"
                               class="regular-text"/>
						<p class="helper"><?php echo __('Check the box to display title in widget/shortcode.', 'mailplatform') ?></p>
					</span>
                </label>
            </div>
            <div class="mp_input_group mp_checkbox_group">
                <input id="mp_show_description" type="checkbox"
                       name="show_description" <?php echo $data->show_description ? 'checked' : '' ?> value="true">
                <label for="mp_show_description">
                    <span class="mp_label"><?php echo __('Short Description', 'mailplatform') ?></span>
					<span class="mp_select">
						<input type="text" name="short_description"
                               value="<?php echo !empty($data) ? stripslashes($data->short_description) : __('A short descriptive text', 'mailplatform') ?>"
                               class="regular-text"/>
						<p class="helper"><?php echo __('Check the box to display description in widget/shortcode.', 'mailplatform') ?></p>
					</span>
                </label>
            </div>
            <div class="mp_input_group mp_checkbox_group mp_dropdown_group">
                <input id="mp_redirect_onsuccess" type="checkbox"
                       name="redirect_onsuccess" <?php echo $data->redirect_onsuccess ? 'checked' : '' ?> value="true">
                <label for="mp_redirect_onsuccess">
                    <span class="mp_label"><?php echo __('Redirect Url', 'mailplatform') ?></span>
					<span class="mp_select">
						<input type="text" name="url" id="redirect_onsuccess_input"
                               value="<?php echo !empty($data) ? stripslashes($data->url) : '' ?>" class="regular-text"/>
						<div class="mp_dropdown" data-for="redirect_onsuccess_input">
                            <ul>
                                <?php foreach ($pages as $item) { ?>
                                    <li><a href="#" data-url="<?php echo $item->guid ?>"><?php echo $item->post_title ?></a></li>
                                <?php } ?>
                            </ul>
                        </div>
						<p class="helper"><?php echo __('Check the box to redirect to the url given in the textbox, or selected from the dropdown on the right.', 'mailplatform') ?></p>
					</span>
                </label>
            </div>
            <div class="mp_input_group">
                <label>
                    <span class="mp_label"><?php echo __('Message: Thank you', 'mailplatform') ?></span>
					<span class="mp_select">
						<input type="text" name="message_success"
                               value="<?php echo !empty($data) ? stripslashes($data->message_success) : __('SUCCESS: You are now subscribed', 'mailplatform') ?>"
                               class="regular-text"/>
					</span>
                </label>
            </div>
            <div class="mp_input_group">
                <label>
                    <span class="mp_label"><?php echo __('Message: Email exists', 'mailplatform') ?></span>
					<span class="mp_select">
						<input type="text" name="message_user"
                               value="<?php echo !empty($data) ? stripslashes($data->message_user) : __('ERROR: This email is already subscribed', 'mailplatform') ?>"
                               class="regular-text"/>
					</span>
                </label>
            </div>
            <div class="mp_input_group">
                <label>
                    <span class="mp_label"><?php echo __('Message: API Error', 'mailplatform') ?></span>
					<span class="mp_select">
						<input type="text" name="message_error"
                               value="<?php echo !empty($data) ? stripslashes($data->message_error) : __('ERROR: There was an error, refresh page and try again', 'mailplatform') ?>"
                               class="regular-text"/>
					</span>
                </label>
            </div>
            <div class="mp_input_group">
                <label>
                    <span class="mp_label"><?php echo __('Button: Submit', 'mailplatform') ?></span>
					<span class="mp_select">
						<input type="text" name="buttontext"
                               value="<?php echo !empty($data) ? stripslashes($data->buttontext) : __('Subscribe', 'mailplatform') ?>"
                               class="regular-text"/>
					</span>
                </label>
            </div>
        </div>
        <?php submit_button(); ?>
    </div>
</form>