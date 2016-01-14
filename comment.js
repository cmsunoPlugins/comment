//
// CMSUno
// Plugin comment
//
function f_load_comment(){
	jQuery(document).ready(function(){
		jQuery.ajax({type:'POST',url:'uno/plugins/comment/comment.php',data:{'action':'load','unox':Unox},dataType:'json',async:true,success:function(r){
			if(r){
				t=document.createElement('table');
				jQuery.each(r,function(k,v){
					d=f_timeConvert(v.d);
					tr=document.createElement('tr');
					td=document.createElement('td');td.className=(v.s<3?'nok':'ok');td.innerHTML=v.t;tr.appendChild(td); // content
					td=document.createElement('td');td.innerHTML=v.n+'<br />'+v.e+'<br />'+v.i;tr.appendChild(td); // username & email
					td=document.createElement('td');td.innerHTML=d;tr.appendChild(td); // date
					td=document.createElement('td');
					d=document.createElement('div');d.className='del';d.onclick=function(){f_del_comment(v.d+v.n);};d.innerHTML='X';td.appendChild(d);
					if(v.s<3){
						d=document.createElement('div');d.className='view';d.onclick=function(){f_ok_comment(v.d+v.n);};d.innerHTML='OK';td.appendChild(d);
					}
					d=document.createElement('div');d.innerHTML='@';d.className=(v.s>0?'melOk':'mel');td.appendChild(d);
					tr.appendChild(td);
					t.appendChild(tr);
				});
				jQuery('#commentL').empty();
				document.getElementById('commentL').appendChild(t);
			}
		}});
	});
}
function f_del_comment(f){
	jQuery(document).ready(function(){
		jQuery.post('uno/plugins/comment/comment.php',{'action':'del','unox':Unox,'del':f},function(r){f_alert(r);f_load_comment();});
	});
}
function f_ok_comment(f){
	jQuery(document).ready(function(){
		jQuery.post('uno/plugins/comment/comment.php',{'action':'ok','unox':Unox,'ok':f},function(r){f_alert(r);f_load_comment();});
	});
}

function f_timeConvert(Timestamp){
	var a=new Date(Timestamp*1000); // ms
	var months=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
	var year=a.getFullYear();
	var month=months[a.getMonth()];
	var date=a.getDate();
	var hour=(a.getHours()>9?a.getHours():'0'+a.getHours());
	var min=(a.getMinutes()>9?a.getMinutes():'0'+a.getMinutes());
	var t=date+' '+month+' '+year+' '+hour+':'+min;
	return t;
}
f_load_comment();
