<?php
require_once( mailplatform__PLUGIN_DIR . '_inc/feed-functions.php' );
$func = new mailplatform_feed_functions();

$img = "data:image/png;base64,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";
?>

<form method="post" action="options.php" id="mailplatform-db-feed-options-form"
      data-message="<?php echo __( 'Do you really want to change settings?' ) ?>">
	<div class="sidebar-box">
		<div class="sidebar-content">

			<?php settings_fields( 'mailplatform-db-feed-options' ); ?>

			<label class="mailplatform-feed-labels">
				<span><?php _e( 'Posts Feed', 'mailplatform' ); ?></span>
				<input name="mailplatform_feed_posts" type="checkbox"
				       value="true" <?php echo $func->posts ? 'checked' : ''; ?>> <?php _e( 'enable/disable', 'mailplatform' ); ?>
				<?php if ( isset( $func->feeds['posts'] ) ) { ?>
					<a href="<?php echo $func->feeds['posts']; ?>"
					   title="<?php echo __( 'Go to Posts RSS page: ', 'mailplatform' ) . $func->feeds['posts']; ?>"
					   target="_blank"><?php echo "{$func->feeds['posts']} <img src='{$img}'>"; ?></a>
				<?php } ?>
			</label class="mailplatform-feed-labels">

			<label class="mailplatform-feed-labels">
				<span><?php _e( 'Pages Feed', 'mailplatform' ); ?></span>
				<input name="mailplatform_feed_pages" type="checkbox"
				       value="true" <?php echo $func->pages ? 'checked' : ''; ?>> <?php _e( 'enable/disable', 'mailplatform' ); ?>
				<?php if ( isset( $func->feeds['pages'] ) ) { ?>
					<a href="<?php echo $func->feeds['pages']; ?>"
					   title="<?php echo __( 'Go to Pages RSS page: ', 'mailplatform' ) . $func->feeds['pages']; ?>"
					   target="_blank"><?php echo "{$func->feeds['pages']} <img src='{$img}'>"; ?></a>
				<?php } ?>
			</label class="mailplatform-feed-labels">

			<?php if ( class_exists( 'WooCommerce' ) ) { ?>
				<label class="mailplatform-feed-labels">
					<span><?php _e( 'WooCommerce Products Feed', 'mailplatform' ); ?></span>
					<input name="mailplatform_feed_woocommerce" type="checkbox"
					       value="true" <?php echo $func->woocommerce ? 'checked' : ''; ?>> <?php _e( 'enable/disable', 'mailplatform' ); ?>
					<?php if ( isset( $func->feeds['woocommerce'] ) ) { ?>
						<a href="<?php echo $func->feeds['woocommerce']; ?>"
						   title="<?php echo __( 'Go to WooCommerce RSS page: ', 'mailplatform' ) . $func->feeds['woocommerce']; ?>"
						   target="_blank"><?php echo "{$func->feeds['woocommerce']} <img src='{$img}'>"; ?></a>
					<?php } ?>
				</label class="mailplatform-feed-labels">
				<?php
			}

			do_settings_sections( 'mailplatform-db-feed-options' );
			submit_button();
			?>
		</div>
	</div>
</form>
<div class="sidebar-box">
	<div class="sidebar-content">
		<p class="mailplatform-headline"><?php _e( 'URL Builder', 'mailplatform' ) ?></p>

		<p><?php _e( 'Build your RSS link here', 'mailplatform' ) ?></p>

		<div class="mailplaform-builder-wrap row">
			<input type="hidden" id="mailplatform-link-site_url" value="<?php echo get_site_url(); ?>">
			<div class="mailplatform-link-builder col-md-4" id="mailplatform-link-selector" >
				<select name="">
					<option value="0"><?php _e( 'Choose RSS Feed', 'mailplatform' ) ?></option>
					<optgroup label="<?php _e( 'Feeds', 'mailplatform' ) ?>">
						<?php if ( $func->posts ) { ?>
							<option value="rss-posts"><?php _e( 'Posts', 'mailplatform' ) ?></option>
						<?php } ?>
						<?php if ( $func->pages ) { ?>
							<option value="rss-pages"><?php _e( 'Pages', 'mailplatform' ) ?></option>
						<?php } ?>
						<?php if ( $func->woocommerce ) { ?>
							<option value="rss-woocommerce"><?php _e( 'WooCommerce', 'mailplatform' ) ?></option>
						<?php } ?>
					</optgroup>
				</select>
			</div>

			<div class="mailplatform-link-builder col-md-6" id="mailplatform-link-amount" style="display: none;">
				<input type="text" value="" placeholder="<?php _e( 'Select amount of items; default value is 0', 'mailplatform' ) ?>">
			</div>

			<div class="mailplatform-link-builder col-md-4" id="mailplatform-link-category" style="display: none;">
				<select name="" id="mailplatform-link-category-select"></select>
			</div>
		</div>

		<pre><a target="_blank" href="" id="mailplatform-link-output"></a></pre>
		<p class="submit">
			<a id="mailplatform-link-clear" class="button button-primary" href="#"><?php _e( 'Clear link', 'mailplatform' ) ?></a>
		</p>
	</div>
</div>

<script id="mailplatform-link-posts-tpl" type="text/template">
	<option value="0"><?php _e('No category selected', 'mailplatform'); ?></option>
	<optgroup label="<?php _e( 'Categories', 'mailplatform' ) ?>">
		<?php foreach(get_terms('category') as $term){ ?>
			<option value="<?php echo $term->term_id ?>"><?php echo $term->name; ?></option>
		<?php } ?>
	</optgroup>
</script>
<script id="mailplatform-link-woocommerce-tpl" type="text/template">
	<option value="0"><?php _e('No category selected', 'mailplatform'); ?></option>
	<optgroup label="<?php _e( 'Categories', 'mailplatform' ) ?>">
		<?php foreach(get_terms('product_cat') as $term){ ?>
			<option value="<?php echo $term->term_id ?>"><?php echo $term->name; ?></option>
		<?php } ?>
	</optgroup>
</script>