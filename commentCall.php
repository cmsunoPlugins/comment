<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])!='xmlhttprequest') {sleep(2);exit;} // ajax request
?>
<?php
include('../../config.php');
include('lang/lang.php');
if (isset($_POST['a']))
	{
	switch(strip_tags($_POST['a']))
		{
		// ********************************************************************************************
		case 'add':
		include '../../template/mailTemplate.php';
		$t = time();
		//
		$q = file_get_contents('../../data/'.strip_tags($_POST['u']).'/comment.json');
		if($q) $a = json_decode($q,true);
		else $a = array();
		$q1 = file_get_contents('../../data/_sdata-'.$sdata.'/commentMail.json');
		$a1 = json_decode($q1,true);
		if(!isset($a1['key'])) exit;
		$key = false;
		$b = 0;
		foreach($a1['mail'] as $k=>$v)
			{
			if($v==strip_tags($_POST['e']))
				{
				$key = $k;
				$b = 1;
				}
			}
		if($key===false) $key = $a1['key'] + 1;
		$i = $_SERVER['REMOTE_ADDR'];
		$ct = 0;
		foreach($a as $r)
			{
			if(date('z',$r['d'])==date('z') && $r['i']==$i) ++$ct; // same IP and more than 3 comment a day
			}
		if($ct>3)
			{
			echo '<strong>'.T_('You have exceeded the quota').'.</strong>';
			exit;
			}
		$c = nl2br(strip_tags($_POST['t']));
		$c = preg_replace('#(<br */?>\s*)+#i','<br />',$c);
		$g = getGravatar(strip_tags($_POST['e']));
		$a[] = array('e'=>$key,'n'=>strip_tags($_POST['n']),'s'=>($a1['vmel']?0:1),'d'=>$t,'u'=>strip_tags($_POST['u']),'t'=>$c,'g'=>$g,'i'=>$i);
		usort($a,'sortDate');
		if(!$b)
			{
			$a1['mail'][$key] = strip_tags($_POST['e']);
			$a1['key'] = $key;
			$out = json_encode($a1);
			file_put_contents('../../data/_sdata-'.$sdata.'/commentMail.json', $out);
			}
		$out = json_encode($a);
		//
		if(file_put_contents('../../data/'.strip_tags($_POST['u']).'/comment.json', $out))
			{
			echo strip_tags($_POST['t']).'<br /><strong>'.T_('Your comment is awaiting moderation.').'</strong>';
			mailAdmin(T_('New Comment').' : '.strip_tags($_POST['u']), strip_tags($_POST['n']).'<br />'.$c, strip_tags($_POST['u']), $bottom, $top, $sdata);
			exit;
			}
		break;
		// ********************************************************************************************
		}
	}
function sortDate($i,$j){return strcmp($i['d'], $j['d']);}
function mailAdmin($tit, $body, $Ubusy, $bottom, $top, $sdata)
	{
	$bottom = str_replace('[[unsubscribe]]','&nbsp;',$bottom);
	$q = file_get_contents('../../data/'.$Ubusy.'/site.json'); $a = json_decode($q,true);
	$q = file_get_contents('../../data/_sdata-'.$sdata.'/ssite.json'); $b = json_decode($q,true);
	$rn = "\r\n";
	$boundary = "-----=".md5(rand());
	$body = "<b>".$tit."</b><br />".$rn.$body.$rn;
	$msgT = strip_tags($body);
	$msgH = $top . $body . $bottom;
	$sujet = $a['tit'].' - '. $tit;
	$fm = preg_replace("/[^a-zA-Z ]+/", "", $a['tit']);
	$header  = "From: ".$fm."<".$b['mel'].">".$rn."Reply-To:".$fm."<".$b['mel'].">";
	$header.= "MIME-Version: 1.0".$rn;
	$header.= "Content-Type: multipart/alternative;".$rn." boundary=\"$boundary\"".$rn;
	$msg= $rn."--".$boundary.$rn;
	$msg.= "Content-Type: text/plain; charset=\"utf-8\"".$rn;
	$msg.= "Content-Transfer-Encoding: 8bit".$rn;
	$msg.= $rn.$msgT.$rn;
	$msg.= $rn."--".$boundary.$rn;
	$msg.= "Content-Type: text/html; charset=\"utf-8\"".$rn;
	$msg.= "Content-Transfer-Encoding: 8bit".$rn;
	$msg.= $rn.$msgH.$rn;
	$msg.= $rn."--".$boundary."--".$rn;
	$msg.= $rn."--".$boundary."--".$rn;
	if(mail($b['mel'], stripslashes($tit), stripslashes($msg), $header)) return true;
	else return false;
	}
function getGravatar($email,$s=80,$d='404',$r='g')
	{
	$u = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($email))) . '?s='.$s.'&d='.$d.'&r='.$r;
	$e = @fopen($u,"r");
	if($e) return str_replace('d=404&','d=mm&',$u);
	else return false;
	}
?>
