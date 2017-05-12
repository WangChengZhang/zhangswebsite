  </div>
  <div class="commentpages">
    <div class="previouspage">前一页</div>
    <div class="nextpage">后一页</div>
  </div>

  <script type="text/javascript">
  $(function(){
  	var username = '<{$username}>';
	var page = 1;
	var totalpage = '<{$totalpage}>';
  	$('.previouspage').click(function(){
		if(page>1){
			$.ajax({type:'POST',
				url:'./back_usercommentpage.php',
				data:"<{$SID}>&page="+(page-1)+"&username="+username,
				success: function(msg){
					$('.nextpage').removeClass('class');
					if(page<=2){$('.previouspage').addClass('class');}
					page = page - 1;
					var obj = eval("("+msg+")");
					$('.comments section').remove();
					for(var i=0;i<obj.length;i++){
						$('.comments').append("<section><h1>"+obj[i].commenttime+"于:</h1><h2><a href='"+obj[i].articlelink+"'>"+obj[i].title+"</a></h2><p>"+obj[i].comment+"</p></section>");
						}
					}
				});
		}
		});
	
	
	$('.nextpage').click(function(){
		if(page<totalpage && totalpage>1){
			$.ajax({type:'POST',
				url:'./back_usercommentpage.php',
				data:"<{$SID}>&page="+(page+1)+"&username="+username,
				success: function(msg){
					$('.previouspage').removeClass('class');
					if(page>=totalpage-1){$('.nextpage').addClass('class');}
					page = page + 1;
					var obj = eval("("+msg+")");
					$('.comments section').remove();
					for(var i=0;i<obj.length;i++){
						$('.comments').append("<section><h1>"+obj[i].commenttime+"于:</h1><h2><a href='"+obj[i].articlelink+"'>"+obj[i].title+"</a></h2><p>"+obj[i].comment+"</p></section>");
						}
					}
				});
		}
		
		});
  });
  </script> 
 
</main>