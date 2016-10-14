<?php
if(!isset($_SESSION['cmsuno'])) exit();
?>
<?php
if(!file_exists('data/'.$Ubusy.'/comment.json')) file_put_contents('data/'.$Ubusy.'/comment.json', '');
if(!file_exists('data/_sdata-'.$sdata.'/commentMail.json')) file_put_contents('data/_sdata-'.$sdata.'/commentMail.json', '{"key":0,"vmel":0,"mail":[]}');
include('plugins/comment/lang/lang.php');
$out = '<div class="commentBloc">
	<div id="comment" class="comment"></div>
	<div class="commentForm">
		<h3>'.T_('Leave a comment').'</h3>
		<p class="commentNote">'.T_('Your email address will not be published. Required fields are marked with *').'</p>
		<p>
			<label for="commentN">'.T_('Name').' *</label>
			<input id="commentN" name="commentN" size="30" type="text"></p>
		<p>
			<label for="commentE">'.T_('Email address').' *</label>
			<input id="commentE" name="commentE" size="30" type="text">
		</p>
		<p>
			<label for="commentT">'.T_('Comment').'</label>
			<textarea id="commentT" name="commentT" cols="45" rows="8"></textarea>
		</p>				
		<div class="commentSubm" onClick="commentSend();">'.T_('Send').'</div>
		<div style="clear:both" id="commentView"></div>					
	</div>
</div><!-- .commentBloc -->'."\r\n";
$Uhtml = str_replace('[[comment]]',$out,$Uhtml);
$Ucontent = str_replace('[[comment]]',$out,$Ucontent);
$Uhead .= '<link rel="stylesheet" href="uno/plugins/comment/commentInc.css" type="text/css" />'."\r\n";
$Ufoot .= '<script type="text/javascript" src="uno/plugins/comment/commentInc.js"></script>'."\r\n";
$unoUbusy = 1; // insert Ubusy with $script
?>
