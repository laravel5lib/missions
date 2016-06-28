<template> 
	<div class="row"> 
		<div class="col-sm-3"> 
			<div class="panel panel-default"> 
				<div class="panel-heading">  <h5><i class="fa fa-cog"></i> Manage</h5> </div>
				 
				<div class="list-group"> 
					<a @click="toView('details')" class="list-group-item" :class="{'active': currentView === 'details'}">Details</a> 
					<a @click="toView('trips')" class="list-group-item" :class="{'active': currentView === 'trips'}">Trips</a> 
					<a @click="toView('regions')" class="list-group-item" :class="{'active': currentView === 'regions'}">Regions</a> 
					<a @click="toView('transports')" class="list-group-item" :class="{'active': currentView === 'transports'}">Transports</a> 
				</div>
				 
			</div>
			  
			<div class="panel panel-default"> 
				<div class="panel-heading">  <h5><i class="fa fa-line-chart"></i> Statistics</h5> </div>
				 
				<div class="list-group"> 
					<a @click="toView('overview')" class="list-group-item"
					   :class="{'active': currentView === 'overview'}">Overview</a> 
				</div>
				 
			</div>
			  
			<div class="panel panel-default"> 
				<div class="panel-heading">  <h5><i class="fa fa-file"></i> Interactions</h5> </div>
				 
				<div class="list-group"> 
					<a @click="toView('sites')" class="list-group-item" :class="{'active': currentView === 'sites'}">Sites</a> 
					<a @click="toView('decisions')" class="list-group-item" :class="{'active': currentView === 'decisions'}">Decisions</a> 
					<a @click="toView('exams')" class="list-group-item" :class="{'active': currentView === 'exams'}">Exams</a> 
				</div>
				 
			</div>
			  
		</div>
		 
		<div class="col-sm-9"> 
			<component :is="currentView" transition="fade" transition-mode="out-in">  </component>
			 
		</div>
		 
	</div>
	 
</template> 
<style> 
.fade-transition {
	 transition: opacity .3s ease;
}

  .fade-enter, .fade-leave {
	 opacity: 0;
}

 </style> 
<script>     import details from './details/details.vue'
import trips from './details/trips.vue'
import regions from './details/regions.vue'
import transports from './details/transports.vue'
export default{
	name: 'admin-campaign-details',
	props: ['campaignId'],
	data(){
		return {currentView: null}
	},
	methods: {
		toView(view){
			this.currentView = view;
		},
	},
	created(){
		this.currentView = 'details';
	},
	components: {
		'details': details,
		'trips': trips,
		'regions': regions,
		'transports': transports,
	}
} </script>