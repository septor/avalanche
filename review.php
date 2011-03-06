<?php

require_once("../../class2.php");
require_once(e_PLUGIN."avalanche/class.php");
require_once(HEADERF);

if(check_class($pref['avalanche_viewaccess'])){
	$text = "
	<table style='width:90%' class='fborder'>
	<tr>
	<td style='width:10%' class='fcaption'>app id</td>
	<td style='width:20%' class='fcaption'>username</td>
	<td style='width:30%' class='fcaption'>question</td>
	<td style='width:40%' class='fcaption'>anwser</td>
	</tr>";
	
	$sql->db_Select("avalanche_request", "*");
	while($row = $sql->db_Fetch()){

		$type = provokeQuestion($row['av_qid'], "type");
		$values = provokeQuestion($row['av_qid'], "value");
		$user = get_user_data($row['av_uid']);
		$values = explode("//", $values);

		if($type == "radio" || $type == "dropdown"){
			$answer = $values[$row['av_value']];

		}else if($type == "checkbox"){
			$checked = explode("//", $row['av_value']);
			$answer = $values[$checked[0]];
			for($i = 1; $i <= (count($checked)-2); $i++){
				$answer .= ", ".$values[$checked[$i]];
			}

		}else{
			$answer = $row['av_value'];
		}

		$text .= "<tr>
		<td class='forumheader3'>".$row['av_aid']."</td>
		<td class='forumheader3'><a href='".e_BASE."user.php?id.".$row['av_uid']."'>".$user["user_name"]."</a></td>
		<td class='forumheader3'>".provokeQuestion($row['av_qid'])."</td>
		<td class='forumheader3'>".$answer."</td>
		</tr>";
	}

	$text .= "</table>";

	$ns->tablerender("Review Submitted Applications", $text);
}else{
	$ns->tablerender("Access Denied! :D", "You do not have the correct access to view this page.");
}
	
require_once(FOOTERF);
?>