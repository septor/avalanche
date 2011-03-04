<?php

function provokeQuestion($qid, $action="key"){
	$provoke = new db();
	$provoke->db_Select("avalanche_application", "*", "av_id='".intval($qid)."'");
	while($row = $provoke->db_Fetch()){
		$question = $row['av_key'];
		$value = $row['av_value'];
		$type = $row['av_type'];
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