<?php

// Creating the widget 
class mailplatform_widget extends WP_Widget {

	function __construct() {

		parent::__construct(
		// Base ID of your widget
			'mailplatform_widget',

			// Widget name will appear in UI
			__( 'MailPlatform', 'mailplatform' ),

			// Widget description
			array( 'description' => __( 'MailPlatform widget for user subscriptions', 'mailplatform' ), )
		);
	}

	// Creating widget front-end
	// This is where the action happens
	public function widget( $args, $instance ) {

		$mailplatform_listid = apply_filters( 'widget_mailplatform_listid', $instance['mailplatform_listid'] );

		// before and after widget arguments are defined by themes
		echo $args['before_widget'];

		mailplatform_WidgetAndShortcode( $mailplatform_listid, 'widget' );

		echo $args['after_widget'];
	}

	// Widget Backend
	public function form( $instance ) {

		$data = mailplatform_get_data();

		if ( isset( $instance['mailplatform_listid'] ) ) {
			$mailplatform_listid = $instance['mailplatform_listid'];
		} else {
			$mailplatform_listid = $data[0]->mailplatform_listid;
		}

		$instance['title'] = $data->title;

		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'mailplatform_listid' ); ?>"><?php _e( 'List:' ); ?></label>
			<select class="widefat" name="<?php echo $this->get_field_name( 'mailplatform_listid' ); ?>"
			        id="<?php echo $this->get_field_id( 'mailplatform_listid' ); ?>">
				<?php foreach ( $data as $item ) { ?>
					<option <?php echo $instance['mailplatform_listid'] == $item->mailplatform_listid ? "selected" : "" ?>
						value="<?php echo $item->mailplatform_listid ?>"><?php echo $item->title ?></option>
				<?php } ?>
			</select>
		</p>
		<?php
	}

	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {

		$data = mailplatform_get_data();

		$instance = array();

		$instance['mailplatform_listid'] = ( ! empty( $new_instance['mailplatform_listid'] ) ) ? strip_tags( $new_instance['mailplatform_listid'] ) : '';
		$instance['title']               = $data->title;

		return $instance;
	}
} // Class mailplatform_widget ends here

// Register and load the widget
function mailplatform_wpb_load_widget() {
	register_widget( 'mailplatform_widget' );
}

?>