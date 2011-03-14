<?php

require_once("../../class2.php");
require_once(e_PLUGIN."avalanche/class.php");
require_once(e_PLUGIN."avalanche/defines.php");
require_once(e_HANDLER."date_handler.php");
require_once(e_HANDLER."mail.php");
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

	if(isset($_POST['submitvote'])){
		if(hasVoted(USERID, $_POST['aid']) == false){
			if($pref['avalanche_forcevotecomment'] == 1){
				if($_POST['vote'] != "" && $_POST['comment'] != ""){
					$sql->db_Insert("avalanche_comment", "'', '".intval(USERID)."', '".intval($_POST['aid'])."', '".$tp->toDB($_POST['comment'])."', '".intval($_POST['vote'])."', '".intval(time())."'");
				}else{
					$message = "You need to select an option and add a comment in order for your vote to count!";
				}
			}else{
				if($_POST['vote'] != ""){
					$sql->db_Insert("avalanche_comment", "'', '".intval(USERID)."', '".intval($_POST['aid'])."', '".$tp->toDB($_POST['comment'])."', '".intval($_POST['vote'])."', '".intval(time())."'");
				}else{
					$message = "You need to select an option in order for your vote to count!";
				}
			}
		}
	}

	if(isset($_POST['updatecomment'])){
		if($pref['avalanche_votecommentediting'] == 1){
			$sql3->db_Update("avalanche_comment", "av_comment='".$tp->toDB($_POST['editcomment'])."' WHERE av_id='".intval($_POST['cid'])."'");
			$message = "You have updated your comment!";
		}
	}

	if(isset($_POST['deleteapp'])){
		$sql->db_Delete("avalanche_request", "av_aid='".intval($_POST['aid'])."'");
		$sql->db_Delete("avalanche_comment", "av_aid='".intval($_POST['aid'])."'");
		$message = "Application #".$_POST['aid']." deleted!";
	}

	if(isset($_POST['acceptapp'])){
		if(getStatus($_POST['aid']) == 0){
			$uem = get_user_data($_POST['uid']);
			if($_POST['replymethod'] == "pm"){
				sendpm(USERID, $_POST['uid'], $tp->toHTML($pref['avalanche_acceptsubject']), str_replace("{GROUPNAME}", $pref['avalanche_groupname'], $tp->toHTML($pref['avalanche_acceptmessage'])));
			}else if($_POST['replymethod'] == "email"){
				sendemail($uem['user_email'], $tp->toHTML($pref['avalanche_acceptsubject']), str_replace("{GROUPNAME}", $pref['avalanche_groupname'], $tp->toHTML($pref['avalanche_acceptmessage'])));
			}
			$message = "Application #".$_POST['aid']." has been accepted via ".$_POST['replymethod']." ";
			if($_POST['andnow'] == "delete"){
				$sql->db_Delete("avalanche_request", "av_aid='".intval($_POST['aid'])."'");
				$sql->db_Delete("avalanche_comment", "av_aid='".intval($_POST['aid'])."'");
				$message .= "and deleted!";
			}else{
				$sql->db_Update("avalanche_request", "av_status='1' WHERE av_aid='".intval($_POST['aid'])."'");
				$message .= "and archived!";
			}
		}
	}

	if(isset($_POST['denyapp'])){
		if(getStatus($_POST['aid']) == 0){
			$uem = get_user_data($_POST['uid']);
			if($_POST['replymethod'] == "pm"){
				sendpm(USERID, $_POST['uid'], $tp->toHTML($pref['avalanche_denysubject']), str_replace("{GROUPNAME}", $pref['avalanche_groupname'], $tp->toHTML($pref['avalanche_denymessage'])));
			}else if($_POST['replymethod'] == "email"){
				sendemail($uem['user_email'], $tp->toHTML($pref['avalanche_denysubject']), str_replace("{GROUPNAME}", $pref['avalanche_groupname'], $tp->toHTML($pref['avalanche_denymessage'])));
			}
			$message = "Application #".$_POST['aid']." has been denied via ".$_POST['replymethod']." ";

			if($_POST['andnow'] == "delete"){
				$sql->db_Delete("avalanche_request", "av_aid='".intval($_POST['aid'])."'");
				$sql->db_Delete("avalanche_comment", "av_aid='".intval($_POST['aid'])."'");
				$message .= "and deleted!";
			}else{
				$sql->db_Update("avalanche_request", "av_status='2' WHERE av_aid='".intval($_POST['aid'])."'");
				$message .= "and archived!";
			}
		}
	}

	if (isset($message)) {
		$ns->tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
	}

	if($action == "id"){
		if($subaction == "edit"){
			if($pref['avalanche_votecommentediting'] == 1){
				$sql3->db_Select("avalanche_comment", "*", "av_id='".intval($subid)."'");
				while($row3 = $sql3->db_Fetch()){
					$comment = $row3['av_comment'];
				}
				$satext = "<form method='post' action='".e_SELF."?id.".$id."'>
				<table style='width:50%' class='fborder'>
				<tr>
				<td style='text-align:center;'><textarea class='tbox' name='editcomment' style='width:80%; height:50px;'>".$comment."</textarea></td>
				</tr>
				<tr>
				<td style='text-align:center;'>
				<input type='hidden' name='cid' value='".$subid."' />
				<input type='submit' class='button' name='updatecomment' value='Update Comment'>
				</td>
				</tr>
				</table>
				</form>";
				$ns->tablerender("Modify your comment below:", "<div style='text-align:center'><b>".$satext."</b></div>");
			}

		}else if($subaction == "delete"){
			$satext = "<form method='post' action='".e_SELF."'>
			<input type='submit' class='button' name='deleteapp' value='Yes!' /> <input type='submit' class='button' value='No!' />
			<input type='hidden' name='aid' value='".$id."' />
			</form>";
			$ns->tablerender("Are you sure you wish to delete the below application?", "<div style='text-align:center'><b>".$satext."</b></div>");

		}else if($subaction == "accept"){
			
			$satext = "<form method='post' action='".e_SELF."'>
			<table style='width:70%' class='fborder'>
			<tr>
			<td style='width:50%;' class='forumheader3'>Reply Method:</td>
			<td style='width:50%; text-align:right;' class='forumheader3'><input type='radio' name='replymethod' value='pm' checked />PM  <input type='radio' name='replymethod' value='email' />Email</td>
			</tr>
			<tr>
			<td style='width:50%;' class='forumheader3'>Action to take after notification has been sent?</td>
			<td style='width:50%; text-align:right;' class='forumheader3'><select name='andnow' class='tbox'>
			<option value='archive'>Archive</option>
			<option value='delete'>Delete</option>
			</select></td>
			</tr>
			<tr>
			<td colspan='2' style='text-align:center;' class='forumheader3'><input type='submit' class='button' name='acceptapp' value='Yes, accept the application!' /> <input type='submit' class='button' value='No, I need more time!' /></td>
			</tr>
			</table>
			<input type='hidden' name='aid' value='".$id."' />
			<input type='hidden' name='uid' value='".getUserid($id)."' />
			</form>";
			$ns->tablerender("Are you sure you wish to accept the below application?", "<div style='text-align:center'><b>".$satext."</b></div>");

		}else if($subaction == "deny"){
			
			$satext = "<form method='post' action='".e_SELF."'>
			<table style='width:70%' class='fborder'>
			<tr>
			<td style='width:50%;' class='forumheader3'>Reply Method:</td>
			<td style='width:50%; text-align:right;' class='forumheader3'><input type='radio' name='replymethod' value='pm' checked />PM  <input type='radio' name='replymethod' value='email' />Email</td>
			</tr>
			<tr>
			<td style='width:50%;' class='forumheader3'>Action to take after notification has been sent?</td>
			<td style='width:50%; text-align:right;' class='forumheader3'><select name='andnow' class='tbox'>
			<option value='archive'>Archive</option>
			<option value='delete'>Delete</option>
			</select></td>
			</tr>
			<tr>
			<td colspan='2' style='text-align:center;' class='forumheader3'><input type='submit' class='button' name='denyapp' value='Yes, deny the application!' /> <input type='submit' class='button' value='No, I need more time!' /></td>
			</tr>
			</table>
			<input type='hidden' name='aid' value='".$id."' />
			<input type='hidden' name='uid' value='".getUserid($id)."' />
			</form>";
			$ns->tablerender("Are you sure you wish to deny the below application?", "<div style='text-align:center'><b>".$satext."</b></div>");

		}

		$user = get_user_data(getUserid($id));

		$text = "";
		if(check_class($pref['avalanche_manageaccess'])){
				$text .= "<table style='width:90%' class='fborder'>
				<tr>
				<td class='forumheader3'>
				<a href='".e_PLUGIN."avalanche/review.php?id.".$id.".accept'>".ACCEPTIMG."</a>
				<a href='".e_PLUGIN."avalanche/review.php?id.".$id.".deny'>".DENYIMG."</a>
				<div style='float:right;'>
				<a href='".e_PLUGIN."avalanche/review.php?id.".$id.".delete'>".DELETEIMG."</a>
				</div>
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
		<br />";
		if(hasVoted(USERID, $id)){
			$yeswhat = (getVotes($id, "yes") == 1 ? "person thinks" : "people think");
			$nowhat = (getVotes($id, "no") == 1 ? "person thinks" : "people think");

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
				if($pref['avalanche_votecommentediting'] == 1 && USERID == $row2['av_uid']){
					$editblock = "<div style='float:right;'><a href='".e_SELF."?id.".$id.".edit.".$row2['av_id']."'>".EDITIMG."</a></div>";
				}else{
					$editblock = "";
				}
				if($row2['av_comment'] != ""){
					$text .= "
					<tr>
					<td style='width:5%; text-align:center;' class='forumheader3'>".($row2['av_vote'] == 0 ? NOIMG : YESIMG)."</td>
					<td style='width:15%;' class='forumheader3'>
					<a href='".e_BASE."user.php?id.".$row2['av_uid']."'>".$cmtusr["user_name"]."</a><br />Total Votes: ".getUservotes($row2['av_uid'])."</td>
					<td style='width:80%; vertical-align:top;' class='forumheader3'>".$tp->toHTML($row2['av_comment']).$editblock."</td>
					</tr>";
				}else{
					$text .= "
					<tr>
					<td style='width:5%; text-align:center;' class='forumheader3'>".($row2['av_vote'] == 0 ? NOIMG : YESIMG)."</td>
					<td colspan='2' style='width:95%;' class='forumheader3'>
					<i><a href='".e_BASE."user.php?id.".$row2['av_uid']."'>".$cmtusr["user_name"]."</a> voted ".($row2['av_vote'] == 0 ? "<span style=color:".$votecolor[1].";'><b>no</b></span>" : "<span style=color:".$votecolor[0].";'><b>yes</b></span>").", but decided not to leave a comment.</i>";
				}
			}
			$text .= "</table>";

			if(check_class($pref['avalanche_manageaccess'])){
				$text .= "<br />
				<table style='width:90%' class='fborder'>
				<tr>
				<td class='forumheader3'>
				<a href='".e_PLUGIN."avalanche/review.php?id.".$id.".accept'>".ACCEPTIMG."</a>
				<a href='".e_PLUGIN."avalanche/review.php?id.".$id.".deny'>".DENYIMG."</a>
				<div style='float:right;'>
				<a href='".e_PLUGIN."avalanche/review.php?id.".$id.".delete'>".DELETEIMG."</a>
				</div>
				</td>
				</tr>
				</table>";
			}

		}else{
			$text .= "<form method='post' action='".e_SELF."?id.".$id."'>
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
			<td style='text-align:center; width:50%;' class='forumheader3'><textarea class='tbox' name='comment' style='width:80%; height:50px;'></textarea></td>
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

		$aids = array();
		$sql->db_Select("avalanche_request", "*");
		while($row = $sql->db_Fetch()){
			if(!in_array($row['av_aid'], $aids)){
				array_push($aids, $row['av_aid']);
			}
		}

		$datesubwidth = (check_class($pref['avalanche_manageaccess']) ? "35" : "50");
		$text = "
		<table style='width:95%' class='fborder'>
		<tr>
		<td style='width:10%; text-align:center;' class='fcaption'>ID</td>
		<td style='width:20%; text-align:center;' class='fcaption'>Username</td>
		<td style='width:".$datesubwidth."%; text-align:center;' class='fcaption'>Date Submitted</td>
		<td style='width:10%; text-align:center;' class='fcaption'>Votes</td>";
		if(check_class($pref['avalanche_manageaccess'])){
			$text .= "<td colspan='3' style='text-align:center;' class='fcaption'>Manage</td>";
		}else{
			$text .= "<td style='width:10%; text-align:center;' class='fcaption'>&nbsp;</td>";
		}
		$text .= "</tr>";
		
		for($i = 0; $i <= (count($aids)-1); $i++){
			$sql2->db_Select("avalanche_request", "*", "av_aid='".intval($aids[$i])."' AND av_status='0' LIMIT 1");
			while($row2 = $sql2->db_Fetch()){
				$user = get_user_data($row2['av_uid']);
				$text .= "<tr>
				<td style='text-align:center;' class='forumheader3'>".(hasVoted(USERID, $row2['av_aid']) == false ? NEWIMG." " : "").$aids[$i]."</td>
				<td style='text-align:center;' class='forumheader3'><a href='".e_BASE."user.php?id.".$row2['av_uid']."'>".$user["user_name"]."</a></td>
				<td style='text-align:center;' class='forumheader3'>".$gen->convert_date($row2['av_datestamp'])."</td>
				<td style='text-align:center;' class='forumheader3'>".getVotes($row2['av_aid'])."</td>
				<td style='text-align:center;' class='forumheader3'><a href='".e_PLUGIN."avalanche/review.php?id.".$aids[$i]."'>view</a></td>";
				if(check_class($pref['avalanche_manageaccess'])){
					$text .= "<td style='text-align:center;' class='forumheader3'>
					<a href='".e_PLUGIN."avalanche/review.php?id.".$aids[$i].".accept'>".ACCEPTIMG."</a>
					<a href='".e_PLUGIN."avalanche/review.php?id.".$aids[$i].".deny'>".DENYIMG."</a>
					</td>
					<td style='width:5%; text-align:center;' class='forumheader3'>
					<a href='".e_PLUGIN."avalanche/review.php?id.".$aids[$i].".delete'>".DELETEIMG."</a>
					</td>";
				}
				$text .= "</tr>";
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