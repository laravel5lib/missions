<template>
	<div style="display:inline;">
		<button class="navbar-toggle" @click="showRight = true">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<aside :show.sync="showRight" placement="right" header="Missions.Me" :width="275">
			<ul class="nav navmenu-nav">
				<li class="donate-nav"><a class="navDonate" href="/fundraisers"><i class="fa fa-heart"></i> Donate To A Cause</a>
				</li>
				<li v-if="auth" id="userMenu" class="dropdown-toggle visible-xs text-center" data-toggle="dropdown"
					aria-haspopup="true" aria-expanded="false">
					<a href="#">
						<img class="img-xs img-circle av-left" :src="avatar" :alt="name"> {{ name }}
					</a>
				</li>
				<template v-if="auth" class="dropdown-menu" aria-labelledby="userMenu">
					<li class="navlabel">Account</li>
					<li class=""><a :href="url">My Profile</a></li>
					<li class=""><a href="/dashboard">Dashboard</a></li>
					<li v-if="admin" class=""><a href="/admin">Admin</a></li>
					<li class=""><a href="/logout">Sign Out</a></li>
				</template>
				<li v-if="!auth"><a href="/login">Login</a></li>
				<li v-if="!auth"><a href="/login">Sign Up</a></li>

				<template v-if="isDashboard()">
					<li class="navlabel">User</li>
					<li><a href="/dashboard"><i class="fa fa-tachometer"></i> Dashboard</a></li>
					<li><a href="/dashboard/settings"><i class="fa fa-cog"></i> Settings</a></li>
					<li><a href="/dashboard/reservations"><i class="fa fa-ticket"></i> Reservations</a></li>
					<li><a href="/dashboard/records"><i class="fa fa-archive"></i> Records</a></li>
					<li v-if="managing"><a href="/dashboard/groups"><i class="fa fa-users"></i> Groups</a></li>
				</template>

				<template v-if="isAdmin()">
					<li class="navlabel">Admin</li>
					<li><a href="/admin"><i class="fa fa-tachometer"></i> Dashboard</a></li>
					<li><a href="/admin/campaigns"><i class="fa fa-globe"></i> Campaings</a></li>
					<li><a href="/admin/reservations/current"><i class="fa fa-ticket"></i> Reservations</a></li>
					<li><a href="/admin/groups"><i class="fa fa-users"></i> Groups</a></li>
					<li><a href="/admin/interests"><i class="fa fa-question-mark"></i> Interests</a></li>
					<li><a href="/admin/users"><i class="fa fa-user"></i> Users</a></li>
					<li><a href="/admin/uploads"><i class="fa fa-picture-o"></i> Uploads</a></li>
				</template>
				<!-- Get Started -->
				<li class="navlabel">Get Started</li>
				<li><a href="/campaigns">Trips</a></li>
				<li><a href="/fundraisers">Fundraisers</a></li>
				<li><a href="/groups">Groups</a></li>
				<li><a href="/support">Partnership</a></li>

				<!-- 1N1D-->
				<li class="navlabel">1Nation1Day</li>
				<li><a href="http://1nation1day.com/">2017 Nicaragua</a></li>
				<li><a href="http://1nation1day.com/dominican">2015 Dominican</a></li>
				<li><a href="http://1nation1day.com/honduras">2013 Honduras</a></li>

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
				<li><a href="/contact">Contact Us</a></li>
			</ul>
		</aside>
	</div>
</template>
<script>
	import VueStrap from 'vue-strap/dist/vue-strap.min';
	export default{
		name: 'top-nav',
		components: {'aside': VueStrap.aside},
		props: {
			'auth': {
				type: Boolean,
				default: false,
				coerce: function (val) {
					return !!parseInt(val);
				}
			},
			'admin': {
				type: Boolean,
				default: false,
				coerce: function (val) {
					return !!parseInt(val);
				}
			},
			'managing': {
				type: Boolean,
				default: false,
				coerce: function (val) {
					return parseInt(val) > 0;
				}
			},
			name: {
				type: String,
				default: null
			},
			avatar: {
				type: String,
				default: null
			},
			url: {
				type: String,
				default: null
			}
		},
		data(){
			return {
				showRight: false,
			}
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
