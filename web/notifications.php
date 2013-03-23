<?php
$pageTitle = 'HappinessXchange - Notifications';
require_once('header.php');
?>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.0.5/angular.min.js"></script>
<div ng-app class="container">

	<div class="row-fluid top-item">
		<div class="span7">
			<div class="logoLong"></div>
		</div>
		<div class="span5 menu">
			<ul>
				<li><div class="menu-profile"></div><a href="/dashboard">My Profile</a></li>
				<li><div class="menu-mobile"></div><a href="javascript:alert('Coming soon...');">Download Mobile App</a></li>
				<li><div class="menu-logout"></div><a href="/logout">Log Out</a></li>
			</ul>
		</div>
	</div>

	<div class="row-fluid">
		<div ng-controller="NotificationsCtrl">

			<div class="row-fluid">
				<div class="span6">
					<div class="pull-left">
						<div style="margin: 20px;">
							Filter: <a ng-click="searchText = {}">All</a> | <a ng-click="searchText.is_read = 1">Read</a> | <a ng-click="searchText.is_read = 0">Unread</a>
						</div>
					</div>
				</div>
				<div class="span6">
					<div class="pull-right">
						<div style="margin: 20px;">
							<span>Total of {{notifications.length}} notifications ({{getNumberUnread()}} unread)</span>
						</div>
					</div>
				</div>
			</div>

			<table class="table table-hover">
				<tr ng-repeat="notification in notifications | filter:searchText" ng-class="{'is_read' : notification.is_read == 1}">
					<td>
						{{$index + 1}}
					</td>
					<td>
						{{notification.message}}
					</td>
					<td>
						<div ng-show="notification.is_read == 0">
							[ <a href="" ng-click="mark_as_read(notification)">mark as read</a> ]
						</div>
						<div ng-hide="notification.is_read == 0">
							[ <a href="" ng-click="mark_as_unread(notification)">mark as unread</a> ]
						</div>
					</td>
					<td>
						<a class="btn btn-danger" href="" ng-click="delete_notification(notification)">
							<i class="icon-trash icon-white"></i>
						</a>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<style>
		.is_read {
			opacity: 0.5;
			background: #CCC;
		}
	</style>

	<script>
		function NotificationsCtrl($scope, $http) {

			$scope.searchText = {};
			$scope.notifications = [];
			$scope.notifications._loading = true;

			$scope.getNumberUnread = function() {
				var _count = 0;
				angular.forEach($scope.notifications, function(notification, key) {
					if (notification.is_read == 0) {
						_count++;
					}
				});
				return _count;
			}

			$scope.get_all_notifications = function() {
				jQuery.getJSON('<?php echo API_ENDPOINT ?>/notifications/?callback=?', {
				}, function(xhrResponse) {
					$scope.$apply(function() {
						$scope.notifications._loading = false;
						$scope.notifications = xhrResponse;
						console.log('$scope.notifications', $scope.notifications);
					});
				});
			};
			$scope.mark_as_read = function(notification) {
				jQuery.getJSON('<?php echo API_ENDPOINT ?>/notification/mark_as_read/?callback=?', {
					notification_id: notification.notification_id
				}, function(xhrResponse) {
					$scope.$apply(function() {
						notification.is_read = 1;
					});
				});
			};

			$scope.mark_as_unread = function(notification) {
				jQuery.getJSON('<?php echo API_ENDPOINT ?>/notification/mark_as_unread/?callback=?', {
					notification_id: notification.notification_id
				}, function(xhrResponse) {
					$scope.$apply(function() {
						notification.is_read = 0;
					});
				});
			};

			$scope.delete_notification = function(notification) {
				if (confirm("Are you sure you want to delete this?")) {
					jQuery.getJSON('<?php echo API_ENDPOINT ?>/notification/delete/?callback=?', {
						notification_id: notification.notification_id
					}, function(xhrResponse) {
						$scope.get_all_notifications();
					});
				}
			};

			(function() {
				// Initialize
				$scope.get_all_notifications();
			})();
		}
	</script>
</div>
<?php require_once('footer.php'); ?>