                <h4>Gifting</h4>

                <div class="row-fluid"> 
                    <div class="span12">
                        <form class="form-inline" method="post" name="give" action="/dashboard/give-gift/<?php if(isset($_GET['id'])) echo $_GET['id']; ?>" target="_self">
                            <div class="span2"><img src="https://graph.facebook.com/<?php if(isset($_GET['id'])) echo $_GET['id']; ?>/picture?type=square"/></div>
                            <div class="span10">
                            	<input class="span8" type="text" name="productURL" id="productURL" placeholder="http://www.amazon.com/gp/product/B007OZNZG0/" value="<?php if(isset($_POST['productURL'])) echo $_POST['productURL']; ?>">
                           		<div class="span10 tips" style="margin:10px 0 0 0;">
                            		<span class="label">Tips</span> Copy and paste the URL of the product website</a>
                        		</div>
                            	<div class="btn btn-primary" onclick="document.getElementById('scrapePane').src='/scrape-product.php?productURL='+document.getElementById('productURL').value">Create Gift</div>
                            </div>
                        </form>
                    </div>
                </div>
                <iframe name="scrapePane" id="scrapePane" src="/scrape-product.php" frameborder="0" width="100%" height="250"></iframe>
                <div class="row-fluid text-center">
                    <button class="btn btn-large btn-primary" onclick="document.give.submit();"><i class="icon-white icon-gift"></i>&nbsp; Give this Gift</button>
                </div>