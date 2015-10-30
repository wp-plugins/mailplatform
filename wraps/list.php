<div class="sidebar-box">
	<div class="row-table">
		<div class="meta-box-sortables ui-sortable">
			<?php
			$count = mailplatform_xmlrequest('lists', 'GetLists', '', true);
			$result = mailplatform_xmlrequest('lists', 'GetLists', "<start>0</start><perpage>10</perpage>");

			if (!class_exists('WP_List_Table')) {
				require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
			}

			$table = new mailplatform_list_table();

			$table->prepare_items();
			$table->display();

			?>
		</div>
	</div>
</div>