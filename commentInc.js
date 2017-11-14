(function(){
	var x=new XMLHttpRequest();
	x.onreadystatechange=function(){
		if(x.readyState==4&&x.status==200&&x.responseText){
			var j=JSON.parse(x.responseText);
			commentView(j);
		}
	};
	x.open('GET','uno/data/'+Ubusy+'/comment.json',true);
	x.send();
})();
function commentSend(){
	var x=new XMLHttpRequest(),p,n,e,t,a;
	e=document.getElementById('commentE').value;
	n=document.getElementById('commentN').value;
	if(n.length>200)n=n.substr(0,200);
	n=n.replace(/[^a-z0-9 _-]/gi,'');
	t=document.getElementById('commentT').value;
	if(t.length>3000)t=t.substr(0,3000)+'.';
	p=encodeURI('a=add&e='+e+'&n='+n+'&t='+t+'&u='+Ubusy);
	x.open('POST','uno/plugins/comment/commentCall.php',true);
	x.setRequestHeader('Content-type','application/x-www-form-urlencoded;charset=utf-8');
	x.setRequestHeader('Content-length',p.length);
	x.setRequestHeader('X-Requested-With','XMLHttpRequest');
	x.setRequestHeader('Connection','close');
	x.onreadystatechange=function(){
		if(x.readyState==4&&x.status==200&&x.responseText){
			o='<div class="comment" style="margin:10px 0">';
			o+='<div class="commentAuthor"><img alt="" src="uno/plugins/comment/img/noPhoto.png" width="80" height="80"><span>'+n+'</span></div>';
			o+='<div class="commentText"><div>'+x.responseText+'</div></div>';
			o+='</div><div style="clear:both"></div>';
			document.getElementById('commentView').innerHTML=o;
			window.scrollTo(0,document.body.scrollHeight);
			document.getElementById('commentE').value='';
			document.getElementById('commentN').value='';
			document.getElementById('commentT').value='';
		}
	};
	a=/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
	if(a.test(e)&&n!=''&&t!='')x.send(p);
}
function commentView(f){
	var i,k,o='<ul>',d=document.getElementById('comment');
	if(d!==null){
		for(i=0;i<f.length;i++){
			if(f[i]['s']==3){
				o+='<li><div id="comment'+i+'">';
				o+='<div class="commentAuthor"><img alt="" src="'+(f[i]['g']?f[i]['g']:'uno/plugins/comment/img/noPhoto.png')+'" width="80" height="80"><span>'+f[i]['n']+'</span> - <time>'+commentDate(f[i]['d'])+'</time></div>';
				o+='<div class="commentText"><div>'+f[i]['t']+'</div></div>';
				o+='</div></li>';
			}
		}
		o+='</ul>';
		d.innerHTML=o;
	}
}
function commentDate(f){
	var d=new Date(f*1000),j,m,a;
	j=((d.getDate()>9)?'':'0')+d.getDate();
	m=((d.getMonth()+1>9)?'':'0')+(d.getMonth()+1);
	a=d.getFullYear();
	return j+'/'+m+'/'+a;
}
