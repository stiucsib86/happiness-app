<div class="row-fluid">
	<ul class="happ-menu nav nav-pills pull-right" style="margin-top: 10px;">
		<li>
			<div>
				<a class="btn btn btn-info" href="javascript:alert('Coming soon...');">
					<i class="icon-download-alt"></i>
					Download Mobile App
				</a>
			</div>
		</li>
		<li>
			<div ng-show="getNumberUnreadNotifications() > 0">
				<a class="btn btn-danger" href="/dashboard/notifications">
					<i class="icon-inbox icon-white"></i>
					{{getNumberUnreadNotifications()}} new notifications
				</a>
			</div>
			<div ng-hide="getNumberUnreadNotifications() > 0">
				<a class="btn" href="/dashboard/notifications">
					<i class="icon-inbox"></i>
					no new notifications
				</a>
			</div>
		</li>
		<li>
			<div class="btn-group">
				<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
					<img ng-src="https://graph.facebook.com/{{auth.user.fb_uid}}/picture?type=square"  style="width: 25px;">
					<span>{{auth.user.email || 'My Profile'}}</span>
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<li>
						<a class="btn btn-link" href="/dashboard">
							<div class="menu-profile"></div>
							Profile
						</a>
					</li>
					<li>
						<a class="btn btn-link" href="/logout">
							<div class="menu-logout"></div>
							Log Out
						</a>
					</li>
				</ul>
			</div>
		</li>
	</ul>

	<div class="pull-left">
		<a href="/dashboard/">
			<div class="logoLong"></div>
		</a>
	</div>
</div>

<style>
	.happ-menu > li {
		margin: auto 2px;
	}
</style>
