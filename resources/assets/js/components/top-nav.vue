<template>
	<li style="display:inline;">
		<button class="navbar-toggle" @click="showRight = true">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span style="color:#f6323e;font-size:7px;text-align:center;text-transform:uppercase;line-height:0em;margin-top:7px;display:block;">Menu</span>
		</button>
		<mm-aside :show="showRight" @open="showRight=true" @close="showRight=false" placement="right" :width="275">
			<div slot="header" class="text-center">
		    <img class="img-xs" style="margin-right:-20px;opacity:.4;margin-top:4px;margin-bottom:4px;" src="/images/mm-icon-lightgray.png" alt="Missions.Me">
		</div>
			<ul class="nav navmenu-nav">
				<li class="navlabel">Account</li>
				<li style="display:inline;" v-if="!auth"><a style="display:inline-block;padding:10px 40px;" href="/login">Login</a></li>
				<li style="display:inline;" v-if="!fundraiser=='1' && !auth"><a style="display:inline-block;padding: 10px 34px;border-left: 1px solid #242424;" href="/give-a-donation">Donate</a></li>
				<template v-if="auth">
					<li class=""><a href="/dashboard/settings" id="menu-profile-link">Profile Settings</a></li>
					<li><a href="/dashboard">Dashboard</a></li>
					<li><a href="/dashboard/reservations">My Trip</a></li>
					<li><a href="/dashboard/records">My Travel Documents</a></li>
					<li v-if="normalizedManaging"><a href="/dashboard/groups">My Team</a></li>
					<li v-if="normalizedManaging"><a href="/dashboard/reports">My Reports</a></li>
					<li v-if="admin" class=""><a href="/admin">Admin Dashboard</a></li>
					<li class=""><a @click="logout"><i class="fa fa-sign-out"></i> Log Out</a></li>
				</template>

				<template v-if="isAdmin()">
					<li class="navlabel">Admin</li>
					<li><a href="/admin">Dashboard</a></li>
					<li><a href="/admin/reservations/current">Reservations</a></li>
					<li><a href="/admin/campaigns">Trips</a></li>
					<li><a href="/admin/users">People</a></li>
					<li><a href="/admin/transactions">Donations</a></li>
					<li><a href="/admin/groups">Organizations</a></li>
					<li><a href="/admin/records">Travel Documents</a></li>
					<li><a href="/admin/reports">Reports</a></li>
				</template>
				<!-- Get Started -->
				<li class="navlabel">Get Started</li>
				<li><a href="/trips">Trips</a></li>
				<li><a href="/fundraisers">Fundraisers</a></li>
				<li><a href="/teams">Teams</a></li>

				<!-- 1N1D-->
				<li class="navlabel">1Nation1Day</li>
				<li><a href="http://1nation1day.com/nicaragua">2017 Nicaragua</a></li>
				<li><a href="http://1nation1day.com/dominican">2015 Dominican</a></li>
				<li><a href="http://1nation1day.com/honduras">2013 Honduras</a></li>

				<!-- Causes -->
				<li class="navlabel">Causes</li>
				<li><a href="/1nation1day">1Nation1Day</a></li>
				<li><a href="https://angelhouse.me/">Rescue Orphans</a></li>
				<li><a href="/water">Clean Water</a></li>
				<li><a href="/medical">Medical Missions</a></li>

				<!-- Training -->
				<li class="navlabel">Train</li>
				<li><a href="/college">Missions.Me College</a></li>
				<li><a href="/speakers">Speakers</a></li>

				<!-- M.Me -->
				<li class="navlabel">About</li>
				<li><a href="/about-mm">Missions.Me</a></li>
				<li><a href="/partnership">Partnership</a></li>
				<li><a href="/contact">Contact Us</a></li>
			</ul>
		</mm-aside>
	</li>
</template>
<script type="text/javascript">
	export default{
		name: 'top-nav',
		props: {
			'managing': {
				type: String,
				default: '0',
			},
			'fundraiser': {
				type: String,
				default: '0'
			}
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
		        return this.auth ? _.contains(_.pluck(this.$root.user.roles.data, 'name'), 'super_admin') : false;
			},
			normalizedManaging() {
                return parseInt(this.managing) > 0;
            },
			name() {
		        return this.auth ? this.$root.user.name : '';
			},
			avatar() {
		        return this.auth ? this.$root.user.avatar : '';
			},
			url() {
		        return this.auth ? '/' + this.$root.user.url : '';
			},
		},
		methods: {
			isDashboard(){
				return this.auth && window.location.pathname.split('/')[1] === 'dashboard';
			},
			isAdmin(){
				return this.admin && window.location.pathname.split('/')[1] === 'admin';
			},
			logout(){
			    this.$http.post('/logout', null, {baseURL: ''}).then((response) => {
                    window.location = '/login';
                });
			}
		},
		mounted(){

		}
	}
</script>
