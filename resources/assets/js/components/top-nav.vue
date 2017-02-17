<template>
	<li style="display:inline;">
		<button class="navbar-toggle" @click="showRight = true">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span style="color:#f6323e;font-size:7px;text-align:center;text-transform:uppercase;line-height:0em;margin-top:7px;display:block;">Menu</span>
		</button>
		<aside :show.sync="showRight" placement="right" :width="275">
		<div slot="header" class="text-center">
		    <img class="img-xs" style="margin-right:-20px;opacity:.4;margin-top:4px;margin-bottom:4px;" src="/images/mm-icon-lightgray.png" alt="Missions.Me">
		</div>
			<ul class="nav navmenu-nav">
				<li class="donate-nav"><a class="navDonate" href="/fundraisers"><i class="fa fa-heart"></i> Donate To A Cause</a>
				</li>
				<li class="navlabel">Account</li>
				<li v-if="auth" id="userMenu" slot="button" class="dropdown-toggle text-center" data-toggle="dropdown">
					<a href="#">
						<img class="img-xs img-circle av-left text-capitalize" :src="avatar" :alt="name"> {{ name }} <i class="fa fa-angle-down"></i>
						</a>
				</li>
				<ul class="dropdown-menu offcanvas-dropdown">
					<template v-if="auth" aria-labelledby="userMenu">
						<li class=""><a :href="url">My Profile</a></li>
						<li class=""><a href="/dashboard">Dashboard</a></li>
						<li v-if="admin" class=""><a href="/admin">Admin</a></li>
						<li class=""><a href="/logout">Sign Out</a></li>
					</template>
				</ul>
				<li style="display:inline;" v-if="!auth"><a style="display:inline-block;padding:10px 40px;" href="/login">Login</a></li>
				<li style="display:inline;" v-if="!auth"><a style="display:inline-block;padding: 10px 34px;border-left: 1px solid #242424;" href="/login">Sign Up</a></li>

				<template v-if="isDashboard()">
					<li class="navlabel">User</li>
					<li><a href="/dashboard"><i class="fa fa-tachometer" style="margin-right:7px;"></i> Dashboard</a></li>
					<li><a href="/dashboard/settings"><i class="fa fa-cog" style="margin-left:1px;margin-right:8px;"></i> Settings</a></li>
					<li><a href="/dashboard/reservations"><i class="fa fa-ticket" style="margin-right:7px;"></i> Reservations</a></li>
					<li><a href="/dashboard/records"><i class="fa fa-archive" style="margin-right:7px;"></i> Records</a></li>
					<li v-if="managing"><a href="/dashboard/groups"><i class="fa fa-users" style="margin-right:7px;"></i> Groups</a></li>
					<li><a href="/dashboard/projects"><i class="fa fa-tint" style="margin-left:3px;margin-right:10px;"></i> Projects</a></li>

				</template>

				<template v-if="isAdmin()">
					<li class="navlabel">Admin</li>
					<li><a href="/admin"><i class="fa fa-tachometer" style="margin-right:7px;"></i> Dashboard</a></li>
					<li><a href="/admin/campaigns"><i class="fa fa-globe" style="margin-left:1px;margin-right:7px;"></i> Campaigns</a></li>
					<li><a href="/admin/reservations/current"><i class="fa fa-ticket" style="margin-right:6px;"></i> Reservations</a></li>
					<li><a href="/admin/groups"><i class="fa fa-users" style="margin-right:5px;"></i> Groups</a></li>
					<li><a href="/admin/users"><i class="fa fa-user" style="margin-left:1px;margin-right:8px;"></i> Users</a></li>
					<li><a href="/admin/uploads"><i class="fa fa-picture-o" style="margin-right:5px;"></i> Uploads</a></li>
					<li><a href="/admin/funds"><i class="fa fa-usd" style="margin-left:2px;margin-right:9px;"></i> Financials</a></li>
					<li><a href="/admin/causes"><i class="fa fa-tint" style="margin-left:2px;margin-right:9px;"></i> Projects</a></li>
					<li><a href="/admin/records"><i class="fa fa-archive" style="margin-right:6px;"></i> Records</a></li>
				</template>
				<!-- Get Started -->
				<li class="navlabel">Get Started</li>
				<li><a href="/campaigns">Trips</a></li>
				<li><a href="/fundraisers">Fundraisers</a></li>
				<li><a href="/groups">Groups</a></li>
				<li><a href="/support">Partnership</a></li>

				<!-- 1N1D-->
				<li class="navlabel">1Nation1Day</li>
				<li><a target="_blank" href="http://1nation1day.com/">2017 Nicaragua</a></li>
				<li><a target="_blank" href="http://1nation1day.com/dominican">2015 Dominican</a></li>
				<li><a target="_blank" href="http://1nation1day.com/honduras">2013 Honduras</a></li>

				<!-- Causes -->
				<li class="navlabel">Causes</li>
				<li><a href="/water">Clean Water</a></li>
				<li><a href="/orphans">Rescue Orphans</a></li>
				<li><a href="/medical">Medical Missions</a></li>
				<!--<li><a href="#">Homes</a></li>-->
				<!--<li><a href="#">Trafficking Rescue</a></li>-->
				<!--<li><a href="#">Leadership Centers</a></li>-->
				<!--<li><a href="#">Sponsor A Project</a></li>-->

				<!-- Training -->
				<li class="navlabel">Train</li>
				<li><a href="/college">Missions.Me College</a></li>
				<li><a href="/speakers">Speakers</a></li>

				<!-- M.Me -->
				<li class="navlabel">About</li>
				<li><a href="/about-mm">Missions.Me</a></li>
				<li><a href="/support">Give To Missions.Me</a></li>
				<li><a href="/contact">Contact Us</a></li>
			</ul>
		</aside>
	</li>
</template>
<script type="text/javascript">
	export default{
		name: 'top-nav',
		props: {
			'managing': {
				type: Boolean,
				default: false,
				coerce: function (val) {
					return parseInt(val) > 0;
				}
			},
		},
		data(){
			return {
				showRight: false,
			}
		},
		computed:{
		    auth() {
		        return !!this.$root.user
			},
			admin() {
		        return this.auth ? _.contains(_.pluck(this.$root.user.roles.data, 'name'), 'admin') : false;
			},
			/*managing() {
		        return this.auth ? this.$root.user : '';
			},*/
			name() {
		        return this.auth ? this.$root.user.name : '';
			},
			avatar() {
		        return this.auth ? this.$root.user.avatar : '';
			},
			url() {
		        return this.auth ? this.$root.user.url : '';
			},
		},
		methods: {
			isDashboard(){
				return this.auth && window.location.pathname.split('/')[1] === 'dashboard';
			},
			isAdmin(){
				return this.admin && window.location.pathname.split('/')[1] === 'admin';
			},
		},
		ready(){

		}
	}
</script>
