                <h4>Gifting</h4>

                <div class="row-fluid"> 
                    <div class="span12">
                        <form class="form-inline" method="post" action="/dashboard/give-gift/<?php if(isset($_GET['id'])) echo $_GET['id']; ?>" target="_self">
                            <input class="span10" type="text" name="productURL" id="productURL" placeholder="http://www.amazon.com/gp/product/B007OZNZG0/" value="<?php if(isset($_POST['productURL'])) echo $_POST['productURL']; ?>"> 
                            <button class="btn btn-primary">Create Gift</button>
                        </form>
                        <div class="span12 tips">
                            <span class="label">Tips</span> Copy and paste the URL of the product website</a>
                        </div>
                    </div>
                </div><?php
				include_once('/plugins/simplehtmldom_1.5/simple_html_dom.php');
				
				function scraping_shop($url) {
					// create HTML DOM
					$html = file_get_html($url);
				
					// find all image
					foreach($html->find('img') as $e) {
					  if(!preg_match("/g-ecx./i", $e->src)) {
						$scrappedData['Image'] = $e->src;
						break;
					  }
					}
				
					// find all span tags with class=gb1
					foreach($html->find('span#btAsinTitle') as $e) {
						$scrappedData['Product'] = $e->innertext;
						break;
					}
				
					// find all span tags with class=gb1
					foreach($html->find('span.s_star_4_5') as $e) {
						$scrappedData['Rating'] = strip_tags($e->innertext);
						break;
					}

					// find all span tags with class=gb1
					foreach($html->find('span.price') as $e) {
						$scrappedData['Price'] = strip_tags($e->innertext);
						break;
					}

					// clean up memory
					$html->clear();
					unset($html);
				
					return $scrappedData;
				}
				
				if($_POST) {
					$scrappedData = scraping_shop($_POST['productURL']); ?>
                <div class="row-fluid">
                    <div class="well">
                       <div class="row-fluid">
                        <div class="span4"><img class="giftbox" src="<?php echo $scrappedData['Image']; ?>"/></div>
                        <div class="span8">
                            <h5><?php echo $scrappedData['Product']; ?></h5>
                            <h5><?php echo $scrappedData['Price']; ?></h5>
                            <h5>Rated <?php echo $scrappedData['Rating']; ?></h5>
                        </div>
                       </div>
                    </div>
                </div>
                <div class="row-fluid text-center">
                    <button class="btn btn-large btn-primary" onClick="giveGift();"><i class="icon-white icon-gift"></i>&nbsp; Give this Gift</button>
                </div><?php
				} ?>