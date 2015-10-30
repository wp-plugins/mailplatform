<div class="sidebar-box">
<form method="post" action="options.php" id="mailplatform-options-form" data-message="<?php echo __('Do you really want to change settings?') ?>">
	<?php settings_fields( 'mailplatform-db-api-options' ); ?>
	<div>
        <div class="mp_input_group">
            <label>
                <span class="mp_label"><?php echo __('XML Path', 'mailplatform') ?></span>
				<span class="mp_select">
					<input type="text" name="mailplatform_api_xml_path" value="<?php echo esc_attr( get_option('mailplatform_api_xml_path') ); ?>"class="regular-text" />
				</span>
            </label>
        </div>

        <div class="mp_input_group">
            <label>
                <span class="mp_label"><?php echo __('Username', 'mailplatform') ?></span>
				<span class="mp_select">
					<input type="text" name="mailplatform_api_username" value="<?php echo esc_attr( get_option('mailplatform_api_username') ); ?>"class="regular-text" />
				</span>
            </label>
        </div>

		<div class="mp_input_group">
			<label>
				<span class="mp_label"><?php echo __('Token', 'mailplatform') ?></span>
				<span class="mp_select">
					<input type="text" name="mailplatform_api_token" value="<?php echo esc_attr( get_option('mailplatform_api_token') ); ?>"class="regular-text" />
				</span>
			</label>
		</div>
	</div>


	<?php
	do_settings_sections( 'mailplatform-db-api-options' );
	submit_button();
	?>
</form>
</div>