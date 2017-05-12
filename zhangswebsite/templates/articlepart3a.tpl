</div>
  <form action="addcomment.php?<{$SID}>" method="post" class="addcomment">
    <h1>发表评论(1000字以内)</h1>
    <textarea name="comment" maxlength="1000" required id="comment"></textarea>
    <button type="submit">提交</button>
  </form>
<script type="text/javascript" src="stylesheets/jquery/jquery-2.2.1.min.js"></script>
<script type="text/javascript">
$(function(){
	$('.addcomment button').click(function(){
		if($('#comment').val()!=null){
		$.ajax({
			url:'./addcomment.php?<{$SID}>',
			type:'POST',
			data:'comment='+$('#comment').val()+'&articleid=<{$articleid}>',
			success: function(msg){
				var obj = eval("("+msg+")");
				if(obj.result=='success'){
					alert('发表成功');
					window.location.reload(true);
					}
				else{
					alert('发表失败');
					}
				}
			});
		}
		return false;
		});
	});
</script>
 </main>