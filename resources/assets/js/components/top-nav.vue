<template>
	<div style="display:inline;">
		<button class="navbar-toggle" @click="showRight = true">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<aside :show.sync="showRight" placement="right" header="Missions.Me" :width="275">
			<ul class="nav navmenu-nav">
				<li class="donate-nav"><a class="navDonate" href="/donate"><i class="fa fa-heart"></i> Donate To A Cause</a>
				</li>
				<li v-if="auth" id="userMenu" class="dropdown-toggle visible-xs text-center" data-toggle="dropdown"
					aria-haspopup="true" aria-expanded="false">
					<a href="#">
						<img class="img-xs img-circle av-left" :src="avatar" :alt="name"> {{ name }}
						<i class="fa fa-angle-down"></i>
					</a>
				</li>
				<ul v-if="auth" class="dropdown-menu" aria-labelledby="userMenu">
					<li class="text-center"><a :href="url">My Profile</a></li>
					<li class="text-center"><a href="/dashboard">Dashboard</a></li>
					<li v-if="admin" class="text-center"><a href="/admin">Admin</a></li>
					<li class="text-center"><a href="/logout">Sign Out</a></li>
				</ul>
				<li v-if="!auth"><a href="#">Login</a></li>
				<li v-if="!auth"><a href="#">Sign Up</a></li>
				<li class="navlabel">1Nation1Day</li>
				<li><a href="http://1nation1day.com/honduras">2013 Honduras</a></li>
				<li><a href="http://1nation1day.com/dominican">2015 Dominican</a></li>
				<li class="navlabel">Trips</li>
				<li><a href="/campaigns">Campaigns</a></li>
				<li class="navlabel">Projects</li>
				<li><a href="why-mm.html">Clean Water</a></li>
				<li><a href="index.html">Rescue Orphans</a></li>
				<li><a href="index.html">Homes</a></li>
				<li><a href="index.html">Trafficking Rescue</a></li>
				<li><a href="index.html">Leadership Centers</a></li>
				<li><a href="index.html">Sponsor A Project</a></li>
				<li class="navlabel">Train</li>
				<li><a href="index.html">Missions.Me College</a></li>
				<li><a href="index.html">Speakers</a></li>
				<li class="navlabel">About</li>
				<li class="active"><a href="about-mm.html">Missions.Me</a></li>
				<li><a href="about-mm.html">Accountability</a></li>
				<li><a href="about-mm.html">Contact Us</a></li>
				<li><a href="about-mm.html">Jobs</a></li>
				<li><a href="blog-post.html">Blog</a></li>
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
		ready(){

		}
	}
</script>
