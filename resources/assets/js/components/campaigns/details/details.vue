<template> 
	<div class="panel panel-default"> 
		<div class="panel-heading">  <h4>Campaign Details</h4> </div>
		 
		<div class="panel-body">  ... </div>
		 
		<div class="panel-footer text-muted small">  Campaign ID: {{ campaignId }} </div>
		 
	</div>
	 
</template> 
<script>
	export default{
		name: 'details', data(){
			return {msg: 'hello vue'}
		}, activate(done){
			done();
		}
	}
</script>