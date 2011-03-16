<?php

require_once("../../class2.php");
require_once(e_PLUGIN."avalanche/class.php");
require_once(e_PLUGIN."avalanche/defines.php");
require_once(e_HANDLER."date_handler.php");
require_once(HEADERF);

if(check_class($pref['avalanche_viewaccess'])){
	if(e_QUERY){
		$tmp = explode(".", e_QUERY);
		$action = $tmp[0];
		$id = $tmp[1];
		$subaction = $tmp[2];
		$subid = $tmp[3];
		unset($tmp);
	}
	$gen = new convert();
	$sql3 = new db();
	$votecolor = explode(",", $pref['avalanche_votecolors']);

	if(isset($_POST['deleteapp'])){
		$sql->db_Delete("avalanche_request", "av_aid='".intval($_POST['aid'])."'");
		$sql->db_Delete("avalanche_comment", "av_aid='".intval($_POST['aid'])."'");
		$message = "Application #".$_POST['aid']." deleted!";
	}

	if (isset($message)) {
		$ns->tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
	}

	if($action == "id"){
		$user = get_user_data(getUserid($id));

		if($subaction == "delete"){
			$satext = "<form method='post' action='".e_SELF."'>
			<input type='submit' class='button' name='deleteapp' value='Yes!' /> <input type='submit' class='button' value='No!' />
			<input type='hidden' name='aid' value='".$id."' />
			</form>";
			$ns->tablerender("Are you sure you wish to delete the below application?", "<div style='text-align:center'><b>".$satext."</b></div>");
		}

		$text = "";
		if(check_class($pref['avalanche_manageaccess'])){
				$text .= "<table style='width:90%' class='fborder'>
				<tr>
				<td style='text-align:right;' class='forumheader3'>
				<a href='".e_PLUGIN."avalanche/archive.php?id.".$id.".delete'>".DELETEIMG."</a>
				</td>
				</tr>
				</table>
				<br />";
			}
		$text .= "<table style='width:90%' class='fborder'>
		<tr>
		<td colspan='2' class='fcaption'>Application by <a href='".e_BASE."user.php?id.".$userid."'>".$user["user_name"]."</a></td>
		</tr>";
		
		$sql->db_Select("avalanche_request", "*", "av_aid='".intval($id)."'");
		while($row = $sql->db_Fetch()){
			$type = a_Info($row['av_qid'], "type");
			$values = a_Info($row['av_qid'], "value");
			$userid = $row['av_uid'];
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
			<td style='text-align:right; width:50%;' class='forumheader3'>".$answer."</td>
			</tr>";
		}

		$text .= "</table>
		<br />
		<table style='width:90%' class='fborder'>
		<tr>
		<td colspan='3' style='text-align:center; width:50%;' class='forumheader3'>
		<span style=color:".$votecolor[0].";'><b>".getVotes($id, "yes")."</b></span> ".(getVotes($id, "yes") == 1 ? "person thinks" : "people think")." this application is good.
		<span style=color:".$votecolor[1].";'><b>".getVotes($id, "no")."</b></span> ".(getVotes($id, "no") == 1 ? "person thinks" : "people think")." this application sucks.
		</td>
		</tr>
		<tr>
		<td colspan='3' class='fcaption'>See what they have to say:</td>
		</tr>";
		$sql2->db_Select("avalanche_comment", "*", "av_aid='".intval($id)."'");
		while($row2 = $sql2->db_Fetch()){
			$cmtusr = get_user_data($row2['av_uid']);
			if($row2['av_comment'] != ""){
				$text .= "
				<tr>
				<td style='width:5%; text-align:center;' class='forumheader3'>".($row2['av_vote'] == 0 ? NOIMG : YESIMG)."</td>
				<td style='width:15%;' class='forumheader3'>
				<a href='".e_BASE."user.php?id.".$row2['av_uid']."'>".$cmtusr["user_name"]."</a><br />Total Votes: ".getUservotes($row2['av_uid'])."</td>
				<td style='width:80%; vertical-align:top;' class='forumheader3'>".$tp->toHTML($row2['av_comment'])."</td>
				</tr>";
			}else{
				$text .= "
				<tr>
				<td style='width:5%; text-align:center;' class='forumheader3'>".($row2['av_vote'] == 0 ? NOIMG : YESIMG)."</td>
				<td colspan='2' style='width:95%;' class='forumheader3'>
				<i><a href='".e_BASE."user.php?id.".$row2['av_uid']."'>".$cmtusr["user_name"]."</a> voted ".($row2['av_vote'] == 0 ? "<span style=color:".$votecolor[1].";'><b>no</b></span>" : "<span style=color:".$votecolor[0].";'><b>yes</b></span>").", but decided not to leave a comment.</i>";
			}
		}
		$text .= "</table>
		<br /><br />
		<div style='text-align:center;'><a href='".e_PLUGIN."avalanche/archive.php'>Return to the Archives</a></div>";

	}else{

		$aids = array();
		$sql->db_Select("avalanche_request", "*");
		while($row = $sql->db_Fetch()){
			if(!in_array($row['av_aid'], $aids)){
				array_push($aids, $row['av_aid']);
			}
		}

		$datesubwidth = (check_class($pref['avalanche_manageaccess']) ? "30" : "40");

		$text = "
		<table style='width:95%' class='fborder'>
		<tr>
		<td style='width:10%; text-align:center;' class='fcaption'>ID</td>
		<td style='width:10%; text-align:center;' class='fcaption'>Status</td>
		<td style='width:20%; text-align:center;' class='fcaption'>Username</td>
		<td style='width:".$datesubwidth."%; text-align:center;' class='fcaption'>Date Submitted</td>
		<td style='width:10%; text-align:center;' class='fcaption'>Votes</td>
		<td style='width:10%; text-align:center;' class='fcaption'>&nbsp;</td>";
		if(check_class($pref['avalanche_manageaccess'])){
			$text .= "<td style='width:10%; text-align:center;' class='fcaption'>Manage</td>";
		}
		$text .= "</tr>";
		
		$pretext = "";
		for($i = 0; $i <= (count($aids)-1); $i++){
			$sql2->db_Select("avalanche_request", "*", "av_aid='".intval($aids[$i])."' AND av_status='1' LIMIT 1");
			while($row2 = $sql2->db_Fetch()){
				$user = get_user_data($row2['av_uid']);
				$pretext .= "<tr>
				<td style='text-align:center;' class='forumheader3'>".$aids[$i]."</td>
				<td style='text-align:center;' class='forumheader3'><span style=color:".$votecolor[0].";'>accepted</span></td>
				<td style='text-align:center;' class='forumheader3'><a href='".e_BASE."user.php?id.".$row2['av_uid']."'>".$user["user_name"]."</a></td>
				<td style='text-align:center;' class='forumheader3'>".$gen->convert_date($row2['av_datestamp'])."</td>
				<td style='text-align:center;' class='forumheader3'>".getVotes($row2['av_aid'])."</td>
				<td style='text-align:center;' class='forumheader3'><a href='".e_PLUGIN."avalanche/archive.php?id.".$aids[$i]."'>view</a></td>";
				if(check_class($pref['avalanche_manageaccess'])){
					$pretext .= "
					<td style='width:5%; text-align:center;' class='forumheader3'>
					<a href='".e_PLUGIN."avalanche/archive.php?id.".$aids[$i].".delete'>".DELETEIMG."</a>
					</td>";
				}
				$pretext .= "</tr>";
			}
		}
		for($i = 0; $i <= (count($aids)-1); $i++){
			$sql3->db_Select("avalanche_request", "*", "av_aid='".intval($aids[$i])."' AND av_status='2' LIMIT 1");
			while($row3 = $sql3->db_Fetch()){
				$user = get_user_data($row3['av_uid']);
				$pretext .= "<tr>
				<td style='text-align:center;' class='forumheader3'>".$aids[$i]."</td>
				<td style='text-align:center;' class='forumheader3'><span style=color:".$votecolor[1].";'>denied</span></td>
				<td style='text-align:center;' class='forumheader3'><a href='".e_BASE."user.php?id.".$row3['av_uid']."'>".$user["user_name"]."</a></td>
				<td style='text-align:center;' class='forumheader3'>".$gen->convert_date($row3['av_datestamp'])."</td>
				<td style='text-align:center;' class='forumheader3'>".getVotes($row3['av_aid'])."</td>
				<td style='text-align:center;' class='forumheader3'><a href='".e_PLUGIN."avalanche/archive.php?id.".$aids[$i]."'>view</a></td>";
				if(check_class($pref['avalanche_manageaccess'])){
					$pretext .= "
					<td style='width:5%; text-align:center;' class='forumheader3'>
					<a href='".e_PLUGIN."avalanche/archive.php?id.".$aids[$i].".delete'>".DELETEIMG."</a>
					</td>";
				}
				$pretext .= "</tr>";
			}
		}

		$text .= ($pretext != "" ? $pretext : "<tr>\n<td colspan='6' style='text-align:center;' class='forumheader3'>No applications founds.</td>\n</tr>");
		$text .= "</table>";
	}
	$ns->tablerender("Application Listing", $text);
}else{
	$ns->tablerender("Access Denied! :D", "<div style='text-align:center;'>You do not have the correct access to view this page.</div>");
}
	
require_once(FOOTERF);
?>