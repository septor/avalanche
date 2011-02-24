<?php

function provokeQuestion($qid, $action="key"){
	$provoke = new db();
	$provoke->db_Select("wowapp_application", "*", "wa_id='".intval($qid)."'");
	while($row = $provoke->db_Fetch()){
		$question = $row['wa_key'];
		$value = $row['wa_value'];
		$type = $row['wa_type'];
	}
	if($action == "key"){
		return $question;
	}else if($action == "value"){
		return $value;
	}else if($action == "type"){
		return $type;
	}else{
		return "";
	}
}

?>