<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5 v-if="!editMode">{{campaign.name}}</h5>
        </div>
        <div class="panel-body">
            <div class="col-sm-12 col-md-8">
                <div class="row">
                    <div class="col-sm-12">
                        <label>Description</label>
                        <p>{{campaign.description}}</p>
                    </div>
                </div>
                <hr class="divider">
                <div class="row">
                    <div class="col-sm-6 text-center">
                        <label>Country</label>
                        <p>{{campaign.country}}</p>
                    </div>
                    <div class="col-sm-6 text-center">
                        <label>Page Source</label>
                        <p>{{campaign.page_src}}</p>
                    </div>
                </div>
                <hr class="divider">
                <div class="row">
                    <div class="col-sm-6 text-center">
                        <label>Groups</label>
                        <p>{{campaign.groups_count}}</p>
                    </div>
                    <div class="col-sm-6 text-center">
                        <label>Status</label>
                        <p>{{campaign.status}}</p>
                    </div><!-- end col -->
                </div>
                <hr class="divider">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <div class="well">
                            <label>Page Url</label>
                            <h5>
                                <a :href="'/' + campaign.page_url">
                                    <span class="text-muted">https://missions.me/</span>{{campaign.page_url}}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div><!-- end col -->
            <div class="col-md-4 col-sm-12 panel panel-default text-center">
                <div class="panel-body">
                    <label>Start Date</label>
                    <p>{{ campaign.started_at | moment 'll' false true }}</p>

                    <hr class="divider">
                    <label>End Date</label>
                    <p>{{ campaign.ended_at | moment 'll' false true }}</p>
  
                    <hr class="divider">
                    <label>Created At</label>
                    <p>{{ campaign.created_at | moment 'lll' }}</p>
       
                    <hr class="divider">
                    <label>Updated At</label>
                    <p>{{ campaign.updated_at | moment 'lll' }}</p>
          			
          			<div v-if="campaign.published_at">
                    	<hr class="divider">
                    	<label>Published At</label>
                    	<p>{{ campaign.published_at | moment 'lll' }}</p>
                    </div>
 
                </div><!-- end panel-body -->
            </div><!-- end col -->
        </div><!-- end panel-body -->
    </div>
</template>
<script type="text/javascript">
	export default{
		name: 'admin-campaign-details',
		props: ['campaignId'],
		data(){
			return {
				campaign: {}
			}
		},
		created(){
			let resource = this.$resource('campaigns{/id}', {'include': 'trips.group'});
			resource.get({id: this.campaignId}).then(function(response) {
				this.campaign = response.body.data;
			});
		}
	}

</script>