<div class="row-fluid top-item">
	<div class="menu pull-right">
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
