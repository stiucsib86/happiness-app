<div class="row-fluid top-item" ng-app ng-cloak>
	<div class="menu pull-right" ng-controller="MenuBarCtrl">
		<ul>
			<li>
				<div ng-show="getNumberUnreadNotifications() > 0">
					<a class="btn btn-danger" href="/dashboard/notifications">
						{{getNumberUnreadNotifications()}} new notifications
					</a>
				</div>
				<div ng-hide="getNumberUnreadNotifications() > 0">
					<a class="btn" href="/dashboard/notifications">
						no new notifications
					</a>
				</div>
			</li>
			<li><div class="menu-profile"></div><a href="/dashboard">My Profile</a></li>
			<li><div class="menu-mobile"></div><a href="javascript:alert('Coming soon...');">Download Mobile App</a></li>
			<li><div class="menu-logout"></div><a href="/logout">Log Out</a></li>
		</ul>
	</div>
	<div class="pull-left">
		<div class="logoLong"></div>
	</div>
</div>

<style>
	[ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
		display: none;
	}
</style>
<script>
	function MenuBarCtrl($scope, $rootScope, $http) {

		$rootScope.user = {};

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
					$rootScope.user._loading = false;
					$rootScope.user = xhrResponse;
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
