<?php

require_once("../../class2.php");
require_once(e_PLUGIN."avalanche/class.php");
require_once(HEADERF);

if(check_class($pref['avalanche_viewaccess'])){
	$text = "This file is for testing purposes only. It will more than likely not be in the final release. The purpose of this file is to display the
	results of the answers submitted on the apply.php page. If you notice anything wonky with the displaying of answers based on the questions,
	please report the error to me. Creating complex applications and answering them in complex ways will help make this phase go by quickly.
	<br /><br />
	Below are the results of all submitted applications:

	<table style='width:90%' class='fborder'>
	<tr>
	<td style='width:10%' class='fcaption'>app id</td>
	<td style='width:10%' class='fcaption'>user</td>
	<td style='width:40%' class='fcaption'>question</td>
	<td style='width:50%' class='fcaption'>answer(s)</td>
	</tr>";

	$sql->db_Select("avalanche_request", "*");
	while($row = $sql->db_Fetch()){

		$question = provokeQuestion($row['av_qid']);
		$type = provokeQuestion($row['av_qid'], "type");
		$values = provokeQuestion($row['av_qid'], "value");

		$user = get_user_data($row['av_uid']);
		$username = "<a href='".e_BASE."user.php?id.".$row['av_uid']."'>".$user["user_name"]."</a>";

		if($type == "radio"){
			$values = explode(",", $values);
			$answer = $values[$row['av_value']];

		}else if($type == "dropdown"){
			$values = explode(",", $values);
			$answer = $values[$row['av_value']];

		}else if($type == "checkbox"){
			$values = explode(",", $values);
			$checked = explode(",", $row['av_value']);
			$answer = $values[$checked[0]];
			for($i = 1; $i <= (count($checked)-2); $i++){
				$answer .= ", ".$values[$checked[$i]];
			}

		}else{
			$answer = $row['av_value'];
		}

		$text .= "<tr>
		<td class='forumheader3'>".$row['av_aid']."</td>
		<td class='forumheader3'>".$username."</td>
		<td class='forumheader3'>".$question."</td>
		<td class='forumheader3'>".$answer."</td>
		</tr>";
	}

	$text .= "</table>";

	$ns->tablerender("Application Results", $text);
}else{
	$ns->tablerender("Access Denied! :D", "You do not have the correct access to view this page.");
}
	
require_once(FOOTERF);
?>