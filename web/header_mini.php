<div class="row-fluid top-item" ng-app>
	<div class="menu pull-right" ng-controller="NotificationsMiniCtrl">
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
<script>
	function NotificationsMiniCtrl($scope, $rootScope, $http) {

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
		}

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

		(function() {
			// Initialize
			$rootScope.get_all_notifications();
		})();
	}
</script>
