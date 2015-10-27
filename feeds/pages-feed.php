<?php

$category = isset($_GET['cat']) ? esc_html( sanitize_text_field( $_GET['cat'] ) ) : null;

$type='pages';

$title = __( "Pages", 'mailplatform' );

require_once( mailplatform__PLUGIN_DIR . "feeds/feed-header.php" );

$query = $func->get_posts( $total, 'page', $category );

if ( $query->have_posts() ) {
	while ( $post = $query->have_posts() ) : $query->the_post();
		if ( $post->post_title != 'rss-woocommerce' && $post->post_title != 'rss-pages' && $post->post_title != 'rss-posts' ) {
			$img = wp_get_attachment_url( get_post_thumbnail_id() );
			?>
			<item>
				<id><?php the_ID(); ?></id>
				<guid><?php the_permalink_rss(); ?></guid>
				<title><?php the_title_rss(); ?></title>
				<description><![CDATA[<?php the_excerpt_rss(); ?>]]></description>
				<text><![CDATA[<?php the_content(); ?>]]></text>
				<?php if ( ! empty( $img ) ) { ?>
					<image>
						<![CDATA[<?php echo '<img src="' . wp_get_attachment_url( get_post_thumbnail_id() ) . '" alt="' . $post->post_title . '"/>'; ?>
						]]>
					</image>
					<image_src><?php echo wp_get_attachment_url( get_post_thumbnail_id() ) ?></image_src>
				<?php } ?>
				<url><?php the_permalink_rss(); ?></url>
				<?php rss_enclosure(); ?>
				<?php do_action( 'rss2_item' ); ?>
			</item>
		<?php } endwhile; ?>
<?php }

require_once( mailplatform__PLUGIN_DIR . "feeds/feed-footer.php" );