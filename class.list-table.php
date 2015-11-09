<?php

class mailplatform_list_table extends WP_List_Table {

	private $data;

	public function __construct() {

		parent::__construct( array(
			'singular' => __( 'MailplatformList', 'mailplatform' ), //singular name of the listed records
			'plural'   => __( 'MailplatformLists', 'mailplatform' ), //plural name of the listed records
			'ajax'     => false //should this table support ajax?

		) );

		$this->ajax = false;
	}

	/**
	 * Retrieve customer’s data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_lists( $per_page = 10, $page_number = 1 ) {

		$offset = ( $page_number - 1 ) * $per_page;

		$result = mailplatform_xmlrequest( 'lists', 'GetLists', "<start>{$offset}</start><perpage>{$per_page}</perpage>" );

		$return = array();
		foreach ( $result->data->item as $item ) {
			$return[] = mailplatform_xml2array( $item );
		}

		return $return;
	}

	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {

		$count = mailplatform_xmlrequest( 'lists', 'GetLists', '', true );

		return $count;
	}

	/** Text displayed when no customer data is available */
	public function no_items() {

		_e( 'No customers avaliable.', 'mailplatform' );
	}

	function column_default( $item, $column_name ) {

		$data = mailplatform_get_data();

		switch ( $column_name ) {
			case 'name':

				return '<strong><a class="row-title" href="?page=mailplatform-list-options&type=edit&id=' . $item['listid'] . '" title="' . __( 'Edit', 'mailplatform' ) . ' “' . $item[ $column_name ] . '”">' . $item[ $column_name ] . '</a></strong>
	      	<div class="row-actions">
				<span class="edit"><a href="?page=mailplatform-list-options&type=edit&id=' . $item['listid'] . '" title="' . __( 'Edit', 'mailplatform' ) . ' “' . $item[ $column_name ] . '”">' . __( 'Edit', 'mailplatform' ) . '</a></span>
			</div>
	      ';

			case 'subscribecount':
				return $item[ $column_name ];
			case 'username':
				return $item[ $column_name ];
			case 'shortcode':

				$_item = null;

				foreach ( $data as $el ) {
					if ( $item['listid'] == $el->mailplatform_listid ) {
						$_item = $el;
					}
				}

				$shortcode = ! empty( $_item ) ? '[mailplatform listid="' . $item['listid'] . '"]' : null;

				return $shortcode;
			case 'createdate':
				return date( 'd-m-Y H:i', $item[ $column_name ] );
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}

	function get_columns() {

		$columns = array(
			'name'           => __( 'Name', 'mailplatform' ),
			'subscribecount' => __( 'Subscribers', 'mailplatform' ),
			'username'       => __( 'Author', 'mailplatform' ),
			'createdate'     => __( 'Date added', 'mailplatform' ),
			'shortcode'      => __( 'Shortcode', 'mailplatform' )
		);

		return $columns;
	}

	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {

		$sortable_columns = array(
			/*
			'name'           =>  array('name', false),
            'subscribecount' =>  array('subscribecount', false),
            'username'       =>  array('username', false),
            'createdate'     =>  array('createdate', false)
			*/
		);

		return $sortable_columns;
	}

	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {

		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );

		$per_page     = $this->get_items_per_page( 'lists_per_page', 10 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( array(
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		) );

		$this->items = self::get_lists( $per_page, $current_page );
	}
}

?>