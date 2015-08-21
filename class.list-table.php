<?php

class mailplatform_list_table extends WP_List_Table {

    private $data;

    function __construct($data) {

        $arr = array();
        foreach ($data->item as $item)
            $arr[] = mailplatform_xml2array($item);

        $this->data = $arr;
    }

    function usort_reorder($a, $b) {

        // If no sort, default to title
        $orderby = (!empty($_GET['orderby'])) ? esc_html($_GET['orderby']) : 'name';
        // If no order, default to asc
        $order = (!empty($_GET['order'])) ? esc_html($_GET['order']) : 'asc';
        // Determine sort order
        $result = strcmp($a[$orderby], $b[$orderby]);

        // Send final sort direction to usort
        return ($order === 'asc') ? $result : -$result;
    }

    function prepare_items() {

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $this->data;
    }

    function get_columns() {

        $columns = array(
            'name'           => __('Name', 'mailplatform'),
            'subscribecount' => __('Subscribers', 'mailplatform'),
            'username'       => __('Author', 'mailplatform'),
            'createdate'     => __('Date added', 'mailplatform'),
            'shortcode'      => __('Shortcode', 'mailplatform')
        );

        return $columns;
    }

    function get_sortable_columns() {

        $sortable_columns = array(
            'name'           =>  array('name', false),
            'subscribecount' =>  array('subscribecount', false),
            'username'       =>  array('username', false),
            'createdate'     =>  array('createdate', false)
        );

        return $sortable_columns;
    }

    function column_default($item, $column_name) {

        $data = mailplatform_get_data();

        switch ($column_name) {
            case 'name':

                return '<strong><a class="row-title" href="?page=mailplatform-list-options&type=edit&id=' . $item['listid'] . '" title="' . __('Edit', 'mailplatform') . ' “' . $item[$column_name] . '”">' . $item[$column_name] . '</a></strong>
	      	<div class="row-actions">
				<span class="edit"><a href="?page=mailplatform-list-options&type=edit&id=' . $item['listid'] . '" title="' . __('Edit', 'mailplatform') . ' “' . $item[$column_name] . '”">' . __('Edit', 'mailplatform') . '</a></span>
			</div>
	      ';

            case 'subscribecount':
                return $item[$column_name];
            case 'username':
                return $item[$column_name];
            case 'shortcode':

                $_item = null;

                foreach ($data as $el)
                    if ($item['listid'] == $el->mailplatform_listid) $_item = $el;

                $shortcode = !empty($_item) ? '[mailplatform listid="' . $item['listid'] . '"]' : null;

                return $shortcode;
            case 'createdate':
                return date('d-m-Y H:i', $item[$column_name]);
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }
}

?>