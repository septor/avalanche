<?php

function a_Info($id, $action="key"){
	$provoke = new db();
	$provoke->db_Select("avalanche_application", "*", "av_id='".intval($id)."'");
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

function getUserid($aid){
	$gd = new db();
	$gd->db_Select("avalanche_request", "*", "av_aid='".intval($aid)."' LIMIT 1");
	while($row = $gd->db_Fetch()){
		$userid = $row['av_uid'];
	}
	return $userid;
}

function getReplies($aid){
	global $pref;
	$grp = new db();
	$fields = explode("//", $pref['avalanche_reviewdisplay']);
	$qs = "";
	for($i = 0; $i <= (count($fields)-2); $i++){
		$values = explode("//", a_Info($fields[$i], "value"));
		$type = a_Info($fields[$i], "type");
		$grp->db_Select("avalanche_request", "*", "av_aid='".intval($aid)."' AND av_qid='".intval($fields[$i])."'");
		while($row = $grp->db_Fetch()){
			if($type == "radio" || $type == "dropdown"){
				$add = $values[$row['av_value']]."//";
			}else if($type == "checkbox"){
				$checked = explode("//", $row['av_value']);
				$add = $values[$checked[0]];
				for($i = 1; $i <= (count($checked)-2); $i++){
					$add .= ", ".$values[$checked[$i]];
				}
				$add .= "//";
			}else{
				$add = $row['av_value']."//";
			}
		}
		$qs .= $add;
	}
	if($qs != ""){
		return substr($qs, 0, -2);
	}else{
		return "No replies.";
	}
}

?>