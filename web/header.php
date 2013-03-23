<?php
if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == "::1") {
	define('FB_APP_ID', '229837820399095');
	define('API_ENDPOINT', 'http://happiness-app.ap01.aws.af.cm/');
} else {
	define('FB_APP_ID', '454150781329329');
	define('API_ENDPOINT', 'http://happiness-app.ap01.aws.af.cm/');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" ng-app ng-cloak>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
			<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
			<title><?php echo $pageTitle; ?></title>

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
			<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.0.5/angular.min.js"></script>
			<script type="text/javascript" src="/plugins/main.js"></script>
	</head>

	<body ng-controller="MainAppCtrl">
		<div id="fb-root"></div>
		<script>
			window.fbAsyncInit = function() {
				// init the FB JS SDK
				FB.init({
					appId: '<?php echo FB_APP_ID ?>', // App ID from the App Dashboard
					channelUrl: '//special.localhost/plugins/facebook/channel.html', // Channel File for x-domain communication
					status: true, // check the login status upon init?
					cookie: true, // set sessions cookies to allow your server to access the session?
					xfbml: true  // parse XFBML tags on this page?
				});

				// Additional initialization code such as adding Event Listeners goes here

			};

			// Load the SDK's source Asynchronously
			// Note that the debug version is being actively developed and might
			// contain some type checks that are overly strict.
			// Please report such bugs using the bugs tool.
			(function(d, debug) {
				var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
				if (d.getElementById(id)) {
					return;
				}
				js = d.createElement('script');
				js.id = id;
				js.async = true;
				js.src = "//connect.facebook.net/en_US/all" + (debug ? "/debug" : "") + ".js";
				ref.parentNode.insertBefore(js, ref);
			}(document, /*debug*/false));

			function fb_login() {
				FB.login(function(response) {

					if (response.authResponse) {
						console.log('Welcome!  Fetching your information.... ');
						//console.log(response); // dump complete info
						access_token = response.authResponse.accessToken; //get access token
						user_id = response.authResponse.userID; //get FB UID

						FB.api('/me', function(response) {
							user_email = response.email; //get user email
							// you can store this data into your database
							jQuery.getJSON("http://happiness-app.ap01.aws.af.cm/auth/?accessToken=" + access_token + "&callback=?", function(data) {
								FB.api('/me/friends', function(response) {
									var friends = JSON.stringify(response.data);
									window.localStorage.setItem("friends", friends);
									//console.log(window.localStorage.getItem("friends"));
									if (window.localStorage.getItem("friends") != 'undefined')
										window.location = '/dashboard';
								});
							});
						});

					} else {
						//user hit cancel button
						console.log('User cancelled login or did not fully authorize.');

					}
				}, {
					scope: 'publish_stream,email'
				});
			}

			(function() {
				var e = document.createElement('script');
				e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
				e.async = true;
				document.getElementById('fb-root').appendChild(e);
			}());
		</script>


		<style>
			[ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
				display: none;
			}
		</style>
		<script>
			function MainAppCtrl($scope, $rootScope, $http) {

				$rootScope.auth = {};

				$rootScope.notifications = [];
				$rootScope.notifications._loading = true;

				$rootScope.getNumberUnreadNotifications = function() {
					var _count = 0;
					angular.forEach($rootScope.notifications, function(notification, key) {
						if (notification.is_read == 0) {
							_count++;
						}
					});
					return _count;
				};

				$rootScope.get_all_notifications = function() {
					jQuery.getJSON('<?php echo API_ENDPOINT ?>/notifications/?callback=?', {
					}, function(xhrResponse) {
						$scope.$apply(function() {
							$rootScope.notifications._loading = false;
							$rootScope.notifications = xhrResponse;
							console.log('$rootScope.notifications', $rootScope.notifications);
						});
					});
				};

				$rootScope.get_user_data = function() {
					jQuery.getJSON('<?php echo API_ENDPOINT ?>/auth/?callback=?', {
					}, function(xhrResponse) {
						$scope.$apply(function() {
							$rootScope.auth._loading = false;
							$rootScope.auth = xhrResponse;
							console.log('$rootScope.user', $rootScope.user);
						});
					});
				};

				(function() {
					// Initialize
					$rootScope.get_all_notifications();
					$rootScope.get_user_data();
				})();

			}
		</script>
