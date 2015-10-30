
<?php

if($mailplatform_listid !== null){
	$result = mailplatform_xmlrequest('lists', 'Delete', "<list_id>{$mailplatform_listid}</list_id>");

	if($result->status !== 'SUCCESS'){
		echo "<h2>{$result->status}</h2>";
		echo "<p>{$result->errormessage}</p>";
	}else{
		redirect();
	}
}else{
	redirect();
}

?>
