<div ng-controller="FacebookFriendsCtrl">
	<div>
		<form class="form-search pull-right" style="margin: 5px 2px;">
			<input type="text" class="input-medium search-query" placeholder="Search..." ng-model="fb_friends_filter">
		</form>
		<h4>Friends</h4>
	</div>
	<div class="my-friends">
		<div class="row-fluid" ng-repeat="fb_friend in fb_friends | filter:fb_friends_filter">
			<div class="span2">
				<img ng-src="https://graph.facebook.com/{{fb_friend.id}}/picture?type=square"/>
			</div>
			<div class="span8">
				<a class="btn btn-link" href="https://www.facebook.com/{{fb_friend.id}}" target="_blank">
					<div ng-bind-html-unsafe="fb_friend.name"></div>
				</a>
			</div>
			<div class="span2">
				<a href="/dashboard/give-gift/{{fb_friend.id}}" target="_self" title="Send a gift">
					<img class="giftbox" src="/images/favicon.png" border="0" />
				</a>
			</div>
		</div>
	</div>
</div>

<script>
	function FacebookFriendsCtrl($scope, $rootScope) {

		$rootScope.fb_friends = [];
		$scope.fb_friends_filter = "";

		$scope.read_facebook_friends = function() {
			var _read_facebook_friends = function() {
				$rootScope.fb_friends = JSON.parse(window.localStorage.getItem("friends"));
				console.log('$rootScope.fb_friends', $rootScope.fb_friends);
			}
			if ($scope.$$phase) {
				_read_facebook_friends();
			} else {
				$scope.$apply(function() {
					_read_facebook_friends();
				});
			}

			//			for (var i = 0; i < friends.length; i++) {
			//				//console.log('name', friends[i]['name'], 'id', friends[i]['id']);
			//				document.write('<div class="row-fluid">');
			//				document.write('    <div class="span2"><img src="https://graph.facebook.com/' + friends[i]['id'] + '/picture?type=square"/></div>');
			//				document.write('    <div class="span8"><a href="https://www.facebook.com/' + friends[i]['id'] + '" target="_blank">' + friends[i]['name'] + '</a></div>');
			//				document.write('<div class="span2"><a href="/dashboard/give-gift/' + friends[i]['id'] + '" target="_self"><img class="giftbox" src="/images/favicon.png" border="0" /></a></div>');
			//				document.write('</div>');
			//			}
		};

		(function() {
			// Initialize
			$scope.read_facebook_friends();
		})();

	}


</script>