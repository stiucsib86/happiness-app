	<script>
		jQuery.getJSON("http://happiness-app.ap01.aws.af.cm/gifting/?id=<?php if(isset($_GET['id'])) echo $_GET['id']; ?>&callback=?", function(data) {
			console.log(data);
			document.getElementById('scrapePane').src='/scrape-product.php?productURL='+data.gifting_url;
			document.getElementById('fbPic').src='https://graph.facebook.com/'+data.receiver_fb_id+'/picture?type=square';
		});
	</script>
                <h4>Accept Gift</h4>
				<iframe name="scrapePane" id="scrapePane" src="/scrape-product.php" frameborder="0" width="100%" height="250"></iframe>
                <div class="row-fluid"> 
                    <div class="span12">
                        <form class="form-inline" method="post" name="receive" action="/dashboard/receive-gift/<?php if(isset($_GET['id'])) echo $_GET['id']; ?>" target="_self">
                            <div class="span2"><img id="fbPic" src="/images/nouser.jpg" /></div>
                            <div class="span10">
                            	<textarea class="span8" name="thankyouNote" id="thankyouNote" cols="30" rows="5"></textarea>
                           		<div class="span10 tips" style="margin:10px 0 0 0;">
                            		<span class="label">Tips</span> Enter a thank you message to your friend!</a>
                        		</div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row-fluid text-center">
                    <button class="btn btn-large btn-primary" onclick="document.receive.submit();"><i class="icon-white icon-gift"></i>&nbsp; Accept this Gift</button>
                </div>