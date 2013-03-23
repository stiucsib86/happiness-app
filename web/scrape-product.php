<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" ng-app ng-cloak>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
			<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
			<title>Scrapper</title>

			<meta name="description" content="An easy to use e-retail solution to sell across web, social media and mobile. " />
			<meta name="keywords" content="" />
			<meta name="robots" content="index, follow" />
			<link rel="icon" href="/images/favicon.png" />
			<link rel="apple-touch-icon" href="/images/mobile-icon.png" />

			<!-- Bootstrap styles for responsive website layout, supporting different screen sizes -->
			<link rel="stylesheet" href="/plugins/bootstrap_2.3.1/css/bootstrap.min.css" type="text/css" media="screen" />
			<link rel="stylesheet" href="/plugins/bootstrap_2.3.1/css/bootstrap-responsive.min.css" type="text/css" media="screen" />
			<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css' />
			<link rel="stylesheet" href="/css/Stylesheet.css" />

			<script type="text/javascript" src="/plugins/jquery-1.9.0.min.js"></script>
			<script type="text/javascript" src="/plugins/bootstrap_2.3.1/js/bootstrap.min.js"></script>
            <style>
			body { background:none; }
			</style>
	</head>
    <body>
<?php
	include_once('plugins/simplehtmldom_1.5/simple_html_dom.php');
	
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
	
	if(isset($_GET['productURL'])) {
		$scrappedData = scraping_shop($_GET['productURL']); ?>
	<div class="row-fluid">
		<div class="well">
		   <div class="row-fluid">
			<div class="span3"><img class="giftbox" src="<?php echo $scrappedData['Image']; ?>"/></div>
			<div class="span7">
				<h5><?php echo $scrappedData['Product']; ?></h5>
				<h5><?php echo $scrappedData['Price']; ?></h5>
				<h5>Rated <?php echo $scrappedData['Rating']; ?></h5>
			</div>
		   </div>
		</div>
	</div>
<?php
	} ?>
    </body>
</html>