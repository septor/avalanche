<?php

require_once("../../class2.php");
require_once(e_PLUGIN."avalanche/class.php");
require_once(HEADERF);

if(check_class($pref['avalanche_viewaccess'])){
	if($pref['avalanche_reviewdisplay'] != ""){

		if(e_QUERY){
			$tmp = explode(".", e_QUERY);
			$action = $tmp[0];
			$id = $tmp[1];
			unset($tmp);
		}

		if($action == "id"){
			$user = get_user_data(getUserid($id));
			$text = "<table style='width:90%' class='fborder'>
			<tr>
			<td colspan='2' class='fcaption'>".$id.". <a href='".e_BASE."user.php?id.".$row['av_uid']."'>".$user["user_name"]."</a></td>
			</tr>";
			
			$sql->db_Select("avalanche_request", "*", "av_aid='".intval($id)."'");
			while($row = $sql->db_Fetch()){
				$type = a_Info($row['av_qid'], "type");
				$values = a_Info($row['av_qid'], "value");
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
				<td style='width:50%' class='forumheader3'><b>".a_Info($row['av_qid'])."</b></td>
				<td style='width:50%' class='forumheader3'>".$answer."</td>
				</tr>";
			}

			$text .= "
			<tr>
			<td colspan='2' style='text-align:right;' class='forumheader3'>
			<i>voting options will go here</i>
			</td>
			</tr>
			<tr>
			<td colspan='2' style='text-align:right;' class='forumheader3'>
			<a href='".e_PLUGIN."avalanche/review.php'>Return to the Application Listing</a>
			</td>
			</tr>
			</table>";
		}else{

			$df = explode("//", $pref['avalanche_reviewdisplay']);
			$fcw = (80 / count($df));

			$aids = array(); // not the same kind of aids, you jackass
			$sql->db_Select("avalanche_request", "*");
			while($row = $sql->db_Fetch()){
				if(!in_array($row['av_aid'], $aids)){
					array_push($aids, $row['av_aid']);
				}
			}

			$text = "
			<table style='width:90%' class='fborder'>
			<tr>
			<td style='width:10%; text-align:center;' class='fcaption'>Application ID</td>
			<td colspan='".count($df)."' style='text-align:center;' class='fcaption'>Applicant Information</td>
			</tr>";
			
			for($i = 0; $i <= (count($aids)-1); $i++){
				$sql2->db_Select("avalanche_request", "*", "av_aid='".intval($aids[$i])."' LIMIT 1");
				while($row2 = $sql2->db_Fetch()){
					$user = get_user_data($row2['av_uid']);

					$uf = explode("//", getReplies($aids[$i]));

					$text .= "<tr>
					<td style='text-align:center; width:10%' class='forumheader3'><a href='".e_PLUGIN."avalanche/review.php?id.".$aids[$i]."'>".$aids[$i]."</a></td>
					<td style='width:10%;' class='forumheader3'><a href='".e_BASE."user.php?id.".$row2['av_uid']."'>".$user["user_name"]."</a></td>";
					foreach($uf as $field){
						$text .= "<td style='width:".$fcw."%;' class='forumheader3'>".$field."</td>";
					}
					$text .= "</tr>";

				}

			}

			$text .= "</table>";
		}
	}else{
		$text = "You do not have any fields designated to be displayed!";
	}
	$ns->tablerender("Review Submitted Applications", $text);
}else{
	$ns->tablerender("Access Denied! :D", "You do not have the correct access to view this page.");
}
	
require_once(FOOTERF);
?>