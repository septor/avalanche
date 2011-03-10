<?php

require_once("../../class2.php");
require_once(e_PLUGIN."avalanche/class.php");
require_once(HEADERF);
include_once(e_HANDLER."date_handler.php");

if(check_class($pref['avalanche_viewaccess'])){
	if(e_QUERY){
		$tmp = explode(".", e_QUERY);
		$action = $tmp[0];
		$id = $tmp[1];
		unset($tmp);
	}
	$gen = new convert();

	if(isset($_POST['submitvote'])){
		if($_POST['vote'] != "" && $_POST['comment'] != ""){
			$sql->db_Insert("avalanche_comment", "'', '".intval(USERID)."', '".intval($_POST['aid'])."', '".$tp->toDB($_POST['comment'])."', '".intval($_POST['vote'])."', '".intval(time())."'");
			$message = "Your vote and comment have been submitted for application #".$_POST['aid']."!";
		}else{
			$message = "You must place your vote and submit a comment regarding your decision.";
		}
	}

	$viewimage = (file_exists(THEME."images/avalanche/view.png") ? THEME."images/avalanche/view.png" : e_PLUGIN."avalanche/images/view.png");
	//$deleteimage = (file_exists(THEME."images/avalanche/delete.png") ? THEME."images/avalanche/delete.png" : e_PLUGIN."avalanche/images/delete.png");
	//$acceptimage = (file_exists(THEME."images/avalanche/accept.png") ? THEME."images/avalanche/accept.png" : e_PLUGIN."avalanche/images/accept.png");
	//$denyimage = (file_exists(THEME."images/avalanche/deny.png") ? THEME."images/avalanche/deny.png" : e_PLUGIN."avalanche/images/deny.png");
	$yesimage = (file_exists(THEME."images/avalanche/yes.png") ? THEME."images/avalanche/yes.png" : e_PLUGIN."avalanche/images/yes.png");
	$noimage = (file_exists(THEME."images/avalanche/no.png") ? THEME."images/avalanche/no.png" : e_PLUGIN."avalanche/images/no.png");

	if (isset($message)) {
		$ns->tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
	}

	if($action == "id"){

		$user = get_user_data(getUserid($id));
		$votecolor = explode(",", $pref['avalanche_votecolors']);

		$text = "
		<table style='width:90%' class='fborder'>
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
		<br /><br />";
		if(hasVoted(USERID, $id)){
			$yeswhat = (getVotes($id, "yes") > 1 ? "people think" : "person thinks");
			$nowhat = (getVotes($id, "no") > 1 ? "people think" : "person thinks");

			$text .= "<table style='width:90%' class='fborder'>
			<tr>
			<td colspan='3' style='text-align:center; width:50%;' class='forumheader3'>
			<span style=color:".$votecolor[0].";'><b>".getVotes($id, "yes")."</b></span> ".$yeswhat." this application is good.
			<span style=color:".$votecolor[1].";'><b>".getVotes($id, "no")."</b></span> ".$nowhat." this application sucks.
			</td>
			</tr>
			<tr>
			<td colspan='3' class='fcaption'>See what they have to say:</td>
			</tr>";
			$sql2->db_Select("avalanche_comment", "*", "av_aid='".intval($id)."'");
			while($row2 = $sql2->db_Fetch()){
				$cmtusr = get_user_data($row2['av_uid']);
				$text .= "
				<tr>
				<td style='width:5%; text-align:center;' class='forumheader3'>".($row2['av_vote'] == 0 ? "<img src='".$noimage."' />" : "<img src='".$yesimage."' />")."</td>
				<td style='width:15%;' class='forumheader3'>
				<a href='".e_BASE."user.php?id.".$row2['av_uid']."'>".$cmtusr["user_name"]."</a><br />Total Votes: ".getUservotes($row2['av_uid'])."<br /></td>
				<td style='width:80%; vertical-align:top;' class='forumheader3'>".$row2['av_comment']."</td>
				</tr>";
			}
			$text .= "</table>";

		}else{
			$text .= "<form method='post' action='".e_SELF."'>
			<table style='width:90%' class='fborder'>
			<tr>
			<td colspan='2' class='fcaption'>Vote & Comment</td>
			</tr>
			<tr>
			<td style='width:50%;' class='forumheader3'>Cast your vote:</td>
			<td style='text-align:right; width:50%;' class='forumheader3'><input type='radio' name='vote' value='1' /> Yes <input type='radio' name='vote' value='0' /> No</td>
			</tr>
			<tr>
			<td style='width:50%;' class='forumheader3'>Comment:</td>
			<td style='text-align:center; width:50%;' class='forumheader3'><textarea class='tbox' name='comment' style='width:100%; height:50px;'></textarea></td>
			</tr>
			<tr>
			<td colspan='2' style='text-align:center;' class='forumheader3'>
			<input type='hidden' name='aid' value='".$id."' />
			<input type='submit' class='button' name='submitvote' value='Place Vote'>
			</td>
			</tr>
			</table>
			</form>";
		}
		$text .= "<br /><br />
		<div style='text-align:center;'><a href='".e_PLUGIN."avalanche/review.php'>Return to the Application Listing</a></div>";
	}else{

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
		<td style='width:10%; text-align:center;' class='fcaption'>ID</td>
		<td style='width:20%; text-align:center;' class='fcaption'>Username</td>
		<td style='width:50%; text-align:center;' class='fcaption'>Date Submitted</td>
		<td style='width:10%; text-align:center;' class='fcaption'>Votes</td>
		<td style='width:10%; text-align:center;' class='fcaption'>&nbsp;</td>
		</tr>";
		
		for($i = 0; $i <= (count($aids)-1); $i++){
			$sql2->db_Select("avalanche_request", "*", "av_aid='".intval($aids[$i])."' LIMIT 1");
			while($row2 = $sql2->db_Fetch()){
				$user = get_user_data($row2['av_uid']);

				$text .= "<tr>
				<td style='text-align:center;' class='forumheader3'>".$aids[$i]."</td>
				<td style='text-align:center;' class='forumheader3'><a href='".e_BASE."user.php?id.".$row2['av_uid']."'>".$user["user_name"]."</a></td>
				<td style='text-align:center;' class='forumheader3'>".$gen->convert_date($row2['av_timestamp'])."</td>
				<td style='text-align:center;' class='forumheader3'>".getVotes($row2['av_aid'])."</td>
				<td style='text-align:center;' class='forumheader3'><a href='".e_PLUGIN."avalanche/review.php?id.".$aids[$i]."'><img src='".$viewimage."' / title='View This Application'></a>";
				$text .= "</td></tr>";

			}

		}

		$text .= "</table>";
	}
	$ns->tablerender("Application Listing", $text);
}else{
	$ns->tablerender("Access Denied! :D", "You do not have the correct access to view this page.");
}
	
require_once(FOOTERF);
?>