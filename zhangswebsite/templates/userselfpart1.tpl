<main>
<script type="text/javascript" src="stylesheets/jquery/jquery-2.2.1.min.js"></script> 
  <script type="text/javascript"><{$commentbutton}></script>
  <script type="text/javascript">
	$(function(){
		$('.changeavatar').click(function(){
			$('.cha').toggleClass('class');
			});
		
		$('.changesex').click(function(){
			$('.chs').toggleClass('class');
			});
		
		$('.changepassword').click(function(){
			$('.chp').toggleClass('class');
			});
			
		$('.chs button').click(function(){
			if($('input[name="sex"]:checked').val()!=null){
				$('.chs h1').html('等待服务器');
				$.ajax({type:'POST',
				url:"./changesex.php",
				data:"<{$SID}>&sex="+$('input[name="sex"]:checked').val(),
				success: function(msg){
					var obj = eval("("+msg+")");
					if(obj.result=='success'){$('.chs h1').html('修改成功');}
					}
					});
				}
			else{$('.chs h1').html('性别不能为空！');}
			return false;
			});
		
		$('.chp button').click(function(){
			if($('#password').val().search(/[^(0-9a-zA-Z)]+/g)!=-1 || $('#oldpassword').val().search(/[^(0-9a-zA-Z)]+/g)!=-1 || $('#password').val()!=$('#passwordconfirm').val()){
				$('.chp h1').html('请输入正确的密码格式');
				}
			else{
				$('.chp h1').html('等待服务器');
				$.ajax({type:'POST',
					data:"<{$SID}>&oldpassword="+$('#oldpassword').val()+"&password="+$('#password').val()+"&passwordconfirm="+$('#passwordconfirm').val(),
					url:"./changepassword.php",
					success: function(msg){
						var obj = eval("("+msg+")");
						if(obj.result=='success'){$('.chp h1').html('修改成功，请重新登陆');
							setTimeout("window.location.href='./login.php?<{$SID}>'",1000);}
						else{$('.chp h1').html('密码错误！');}
						}
					});
				}
			return false;		
			});
		
		}); 
  </script> 
  <header>
    <h1><{$username}></h1>
  </header>
  <img src="<{$useravatar}>" alt="" width="150" height="150" class="avatar">
  <div class="changeavatar">修改头像</div>
  <form action="./changeavatar.php" method="post" enctype="multipart/form-data" class="cha">
    <input name="MAX_FILE_SIZE" type="hidden" id="MAX_FILE_SIZE" value="51200">
    <input name="avatar" type="file" required id="avatar">
    <p>选择图片文件，png格式，不超过50kB</p>
    <button type="submit">提交</button>
    <h1></h1>
  </form>
  <div class="sex"><{$sex}></div>
  <div class="changesex">修改性别</div>
  <form action="./changesex.php" method="post" class="chs">
    <h2>
      <input name="sex" type="radio" value="male" class="sex">
      <p>男</p>
      <input name="sex" type="radio" value="female" class="sex">
      <p>女</p>
    </h2>
    <button type="submit">提交</button>
    <h1></h1>
  </form>
  <div class="changepassword">修改密码</div>
  <form action="changepassword.php" method="post" class="chp">
    <input name="oldpassword" type="password" required id="oldpassword" maxlength="30">
    <h2>旧密码(字母或数字，不超过30位)</h2>
    <input name="password" type="password" required id="password" maxlength="30">
    <h3>新密码(字母或数字，不超过30位)</h3>
    <input name="passwordconfirm" type="password" required id="passwordconfirm" maxlength="30">
    <h4>确认新密码</h4>
    <button type="submit">提交</button>
    <h1></h1>
  </form>
  <div class="comments">
    <h1>评论历史</h1>
 
  