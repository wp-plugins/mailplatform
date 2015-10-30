<?php

$category = isset($_GET['cat']) ? esc_html( sanitize_text_field( $_GET['cat'] ) ) : null;

$type='posts';

$title = __( "Posts", 'mailplatform' );

require_once(mailplatform__PLUGIN_DIR . "feeds/feed-header.php");

$query = $func->get_posts($total, 'post', $category);

if ($query->have_posts()) {
	while ($post = $query->have_posts()) : $query->the_post();
		$img = wp_get_attachment_url(get_post_thumbnail_id());
		$product_cats = wp_get_post_terms($post->ID, 'category');

		$cats = array(
			'names' => array(),
			'ids' => array()
		);
		foreach ($product_cats as $cat) {
			$cats['names'][] = $cat->name;
			$cats['links'][] = get_site_url() . "/rss-posts?cat={$cat->term_id}";
		}

		$categories = implode( ',', $cats['names'] );
		$links = implode( ',', $cats['links'] );

		?>
		<item>
			<id><?php the_ID(); ?></id>
			<guid><?php the_permalink_rss(); ?></guid>
			<title><?php the_title_rss(); ?></title>
			<text><![CDATA[<?php the_content(); ?>]]></text>
			<description><![CDATA[<?php the_excerpt_rss(); ?>]]></description>
			<?php if (!empty($img)) { ?>
				<image><![CDATA[<?php echo '<img src="' . wp_get_attachment_url(get_post_thumbnail_id()) . '" alt="' . $post->post_title . '"/>'; ?>]]></image>
				<image_src><?php echo wp_get_attachment_url(get_post_thumbnail_id()) ?></image_src>
			<?php } ?>
			<url><?php the_permalink_rss(); ?></url>
			<categories><?php echo $categories; ?></categories>
			<category_links><?php echo $links; ?></category_links>
			<category_feeds>
				<?php foreach ($cats['links'] as $link) {?>
					<url><?php echo $link; ?></url>
				<?php } ?>
			</category_feeds>
			<?php rss_enclosure(); ?>
			<?php do_action('rss2_item'); ?>
		</item>
	<?php endwhile; ?><?php }

require_once(mailplatform__PLUGIN_DIR . "feeds/feed-footer.php");