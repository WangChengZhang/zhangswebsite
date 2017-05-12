<main>
<form action="./back_login.php" method="post" class="login">
<input name="username" type="text" required id="username" maxlength="20"><h1>用户名</h1>
<input name="password" type="password" required id="password" maxlength="30"><h2>密码</h2>
<input name="securitycode" type="text" required id="securitycode" maxlength="6"><img src="./vcodepng.php" width="100" height="30"/><h3>验证码(区分大小写)</h3>
<h4> </h4>
<button>登陆</button><a href="./signin.php">注册</a>
<input type="hidden" name="<{$SESSNAME}>" value="<{$SESSID}>">
</form>
<script type="text/javascript" src="stylesheets/jquery/jquery-2.2.1.min.js"></script>
<script type="text/javascript">
$(function(){
	var cvcode=false;
	var result='';
	
	$('#securitycode').blur(function(){
		$('.login h3').removeClass('class1').removeClass('class2').html('等待服务器请刷新页面');
		$.ajax({type:'POST',
		url:"./back_login.php",
		data:"<{$SID}>&securitycode="+$(this).val(),
		success: function(msg){
			var obj = eval("("+msg+")");
			if(obj.vcode=="ok"){
				$('.login h3').addClass('class2').removeClass('class1').html('ok');
				cvcode=true;
				}
			else{
				$('.login h3').addClass('class1').removeClass('class2').html('错误');
				}
			}
		});
		});
		
	$('.login img').click(function(){
		$(this).attr('src','./vcodepng.php?<{$SID}>&'+Math.random())
		});
		
		
	$('.login button').click(function(){
		if(cvcode == false){return false;}
		else{
			$.ajax({type:'POST',
		url:"./back_login.php",
		data:"<{$SID}>&securitycode="+$('#securitycode').val()+"&username="+$('#username').val()+"&password="+$('#password').val(),
		success: function(msg){
				var obj = eval("("+msg+")");
				result = obj.result;
				if (obj.vcode != 'ok'){
					$('.login h3').addClass('class1').removeClass('class2').html('错误');
				}
				if(obj.result=="fail"){
					$('.login h4').html('用户名或密码错误');
					}
				else if(obj.result=="success"){
					$('.login h4').html('登录成功');
					setTimeout("window.location.href='./index.php?<{$SID}>'",1000);
					}
				}
			});	
			return false;	
		}
	});
});
</script>
</main>