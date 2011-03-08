<?php

if(!defined("e107_INIT")) {
	require_once("../../class2.php");
}
if(!getperms("P")){ header("location:".e_BASE."index.php"); exit;}
require_once(e_ADMIN."auth.php");

if(isset($_POST['updatedisplay'])){
	$rd = $_POST['reviewdisplay'];
	$display = "";
	if(!empty($rd)){
		for($i = 0; $i < count($rd); $i++){
			$display .= $rd[$i]."//";
		}
	}
	$pref['avalanche_reviewdisplay'] = $display;
	save_prefs();
	$message = "You have successfully updated what is shown on the review page.";
}

if (isset($message)) {
	$ns->tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
}

$text = "<div style='text-align:center'>
	<form method='post' action='".e_SELF."'>
	<table style='width:90%' class='fborder'>
	<tr>
	<td style='width:50%' class='forumheader3'>
	Select which fields you want displayed on the review page.<br />
	The application ID and the user's username will be displayed as well.<br />
	While there is no limit; it's probably best (for layout reasons) if you don't choose more than 3.<br />
	</td>
	<td style='width:50%; text-align:right' class='forumheader3'>";

	$sql->db_Select("avalanche_application", "*");
	$rdtext = "";
	while($row = $sql->db_Fetch()){
		$display = explode("//", $pref['avalanche_reviewdisplay']);
		if(in_array($row['av_id'], $display)){
			$rdtext .= $row['av_key']." <input type='checkbox' name='reviewdisplay[]' value='".$row['av_id']."' checked /><br />";
		}else{
			$rdtext .= $row['av_key']." <input type='checkbox' name='reviewdisplay[]' value='".$row['av_id']."' /><br />";
		}
	}

	$text .= ($rdtext != "" ? $rdtext : "You need to create some fields before you can pick which ones are displayed!");

	$text .= "</td>
	</tr>
	<tr>
	<tr>
	<td colspan='2' style='text-align:center' class='forumheader'>
	<input class='button' type='submit' name='updatedisplay' value='Update Display Fields' />
	</td>
	</tr>
	</table>
	</form>
	</div>";

$ns->tablerender("Configure Avalanche: Review Display", $text);

require_once(e_ADMIN."footer.php");

?>