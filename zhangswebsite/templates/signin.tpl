<main>
  <form action="./back_signin.php" method="post" class="signin">
    <input name="username" type="text" required id="username" maxlength="20">
    <h1>用户名(字母或数字，不超过20位)</h1>
    <input name="password" type="password" required id="password" maxlength="30">
    <h2>密码(字母或数字，不超过30位)</h2>
    <input name="passwordconfirm" type="password" required id="passwordconfirm" maxlength="30">
    <h3>确认密码</h3>
    <input name="sex" type="radio" value="male" class="sex">
    <p>男</p>
    <input name="sex" type="radio" value="female" class="sex">
    <p>女</p>
    <input name="securitycode" type="text" required id="securitycode" maxlength="6">
    <img src="./vcodepng.php" width="100" height="30"/>
    <h4>验证码(区分大小写)</h4>
    <button type="submit">注册</button>
    <input type="hidden" name="<{$SESSNAME}>" value="<{$SESSID}>">
  </form>
  <script type="text/javascript" src="stylesheets/jquery/jquery-2.2.1.min.js"></script> 
  <script type="text/javascript">
  $(function(){
	  var cusername=false;
	  var cvcode=false;
	  var cpassword=false;
	  
	  
	  $('#username').blur(function(){
		  if($(this).val()){
			  if($(this).val().search(/[^(0-9a-zA-Z)]+/g)!=-1){
				  $('.signin h1').addClass('class1').removeClass('class2').html('请输入字母或数字！');
				  }
				else{
					$('.signin h1').removeClass('class1').removeClass('class2').html('等待服务器');
					 $.ajax({type:'POST',
		url:"./back_signin.php",
		data:"<{$SID}>&username="+$(this).val(),
		success: function(msg){
			var obj = eval("("+msg+")");
			if(obj.username=="ok"){
				$('.signin h1').addClass('class2').removeClass('class1').html('可以使用');
				cusername=true;
				}
			else{
				$('.signin h1').addClass('class1').removeClass('class2').html('已注册');
				}
			}
		});
					  }
			  }
		  });
		  
		  
		  
	$('#password').blur(function(){
		if($(this).val()){
			if($(this).val().search(/[^(0-9a-zA-Z)]+/g)!=-1){
				  $('.signin h2').addClass('class1').removeClass('class2').html('请输入字母或数字！');
				  }
			else{
				$('.signin h2').addClass('class2').removeClass('class1').html('ok');
				}
			}
		});
		
		
		
	$('#passwordconfirm').blur(function(){
		if($(this).val()){
			if($(this).val().search(/[^(0-9a-zA-Z)]+/g)!=-1){
				  $('.signin h3').addClass('class1').removeClass('class2').html('请输入字母或数字！');
				  }
			else{
				if($(this).val() == $('#password').val()){
					$('.signin h3').addClass('class2').removeClass('class1').html('ok');
					cpassword=true;
					}
				else{$('.signin h3').addClass('class1').removeClass('class2').html('前后密码不一致！');}
				}
			}
		});
		
		
		
	$('.signin img').click(function(){
		$(this).attr('src','./vcodepng.php?<{$SID}>&'+Math.random())
		});
	
	
	$('#securitycode').blur(function(){
		$('.signin h4').removeClass('class1').removeClass('class2').html('等待服务器');
		$.ajax({type:'POST',
		url:"./back_signin.php",
		data:"<{$SID}>&securitycode="+$(this).val(),
		success: function(msg){
			var obj = eval("("+msg+")");
			if(obj.vcode=="ok"){
				$('.signin h4').addClass('class2').removeClass('class1').html('ok');
				cvcode=true;
				}
			else{
				$('.signin h4').addClass('class1').removeClass('class2').html('错误');
				}
			}
		});
		});
		
		
	$('.signin').submit(function(){
		if(!((cusername==true)&&(cpassword==true)&&(cvcode==true))){
			return false;
			}
		})
		  
	  });
  </script> 
</main>