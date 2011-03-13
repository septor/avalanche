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

function hasVoted($uid, $aid){
	$hv = new db();
	return ($hv->db_Count("avalanche_comment", "(*)", "WHERE av_uid='".intval($uid)."' AND av_aid='".intval($aid)."'") > 0 ? true : false);
}

function getVotes($aid, $type="all"){
	$gv = new db();
	if($type == "all"){
		$votes = $gv->db_Count("avalanche_comment", "(*)", "WHERE av_aid='".intval($aid)."'");
	}else if($type == "yes"){
		$votes = $gv->db_Count("avalanche_comment", "(*)", "WHERE av_aid='".intval($aid)."' AND av_vote='1'");
	}else if($type == "no"){
		$votes = $gv->db_Count("avalanche_comment", "(*)", "WHERE av_aid='".intval($aid)."' AND av_vote='0'");
	}
	return $votes;
}

function getUserVotes($uid){
	$uv = new db();
	return $uv->db_Count("avalanche_comment", "(*)", "WHERE av_uid='".intval($uid)."'");
}

function sendpm($to, $from, $subject, $message){
	global $tp;
	$spm = new db();
	return $spm->db_Insert("private_msg", "0, '".intval($to)."', '".intval($from)."', '".intval(time())."', '0', '".$tp->toDB($subject)."', '".$tp->toDB($message)."', '1', '0', '', '', '".intval(strlen($message))."'");
}

function getStatus($aid){
	$cs = new db();
	$cs->db_Select("avalanche_request", "*", "av_aid='".intval($aid)."' LIMIT 1");
	while($row = $cs->db_Fetch()){
		$status = $row['av_status'];
	}
	return $status;
}

?>