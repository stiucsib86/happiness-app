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
			<li>
				<div class="btn-group">
					<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
						{{auth.user.email || 'My Profile'}}
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
			<li>
				<a class="btn btn-link" href="javascript:alert('Coming soon...');">
					<div class="menu-mobile"></div>
					Mobile App
				</a>
			</li>
			<li>

			</li>
		</ul>
	</div>
	<div class="pull-left">
		<div class="logoLong"></div>
	</div>
</div>
