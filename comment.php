<?php
session_start(); 
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])!='xmlhttprequest') {sleep(2);exit;} // ajax request
if(!isset($_POST['unox']) || $_POST['unox']!=$_SESSION['unox']) {sleep(2);exit;} // appel depuis uno.php
?>
<?php
include('../../config.php');
include('lang/lang.php');
$q = file_get_contents('../../data/busy.json'); $a = json_decode($q,true); $Ubusy = $a['nom'];
// ********************* actions *************************************************************************
if(isset($_POST['action']))
	{
	switch ($_POST['action'])
		{
		// ********************************************************************************************
		case 'plugin': ?>
		<link rel="stylesheet" type="text/css" media="screen" href="uno/plugins/comment/comment.css" />
		<div class="blocForm">
			<h2><?php echo T_("Comment");?></h2>
			<p><?php echo T_("This plugin allows visitors to add comments.");?></p>
			<p><?php echo T_("Just insert the code");?>&nbsp;<code>[[comment]]</code>&nbsp;<?php echo T_("in the template or the page content.");?></p>
			<div id="usersList">
				<h3><?php echo T_("Comments");?></h3>
				<div id="commentL"></div>
			</div>
			<div class="clear"></div>
		</div>
		<?php break;
		// ********************************************************************************************
		case 'load':
		if(file_exists('../../data/'.$Ubusy.'/comment.json') && file_exists('../../data/_sdata-'.$sdata.'/commentMail.json'))
			{
			$q = file_get_contents('../../data/'.$Ubusy.'/comment.json'); $a = json_decode($q,true);
			$q1 = file_get_contents('../../data/_sdata-'.$sdata.'/commentMail.json'); $a1 = json_decode($q1,true);
			if($a) foreach($a as $k=>$v)
				{
				if(isset($a1['mail'][$v['e']])) $a[$k]['e'] = $a1['mail'][$v['e']];
				}
			$out = json_encode($a);
			echo $out;
			}
		else echo false;
		exit;
		break;
		// ********************************************************************************************
		case 'del':
		$l = $_POST['del'];
		if(file_exists('../../data/'.$Ubusy.'/comment.json') && $l)
			{
			$q = file_get_contents('../../data/'.$Ubusy.'/comment.json');
			$a = json_decode($q,true);
			if($a)
				{
				foreach($a as $k=>$v)
					{
					if($v['d'].$v['n']==$l) unset($a[$k]);
					}
				$out = json_encode($a);
				if(file_put_contents('../../data/'.$Ubusy.'/comment.json', $out)) echo T_('Comment deleted');
				else echo '!'.T_('Error');
				}
			}
		else echo '!'.T_('No data');
		break;
		// ********************************************************************************************
		case 'ok':
		$l = $_POST['ok'];
		if(file_exists('../../data/'.$Ubusy.'/comment.json') && $l)
			{
			$q = file_get_contents('../../data/'.$Ubusy.'/comment.json');
			$a = json_decode($q,true);
			if($a)
				{
				foreach($a as $k=>$v)
					{
					if($v['d'].$v['n']==$l) $a[$k]['s'] = 3; // +1 : email valided ; +2 : moderation OK
					}
				$out = json_encode($a);
				if(file_put_contents('../../data/'.$Ubusy.'/comment.json', $out)) echo T_('Comment valided');
				else echo '!'.T_('Error');
				}
			}
		else echo '!'.T_('No data');
		break;
		// ********************************************************************************************
		}
	clearstatcache();
	exit;
	}
?>
