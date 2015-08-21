<div class="sidebar-box">
<div class="row-table">
<?php
$result = mailplatform_xmlrequest('lists', 'GetLists', '');

if( ! class_exists( 'WP_List_Table' ) ) {
require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

$table = new mailplatform_list_table($result->data);
$table->prepare_items();
$table->display();

?>
</div>
	
</div>