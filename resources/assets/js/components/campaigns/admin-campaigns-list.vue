<template>
	<div>
		<div class="row">
			<div class="col-sm-12">
				<form class="form-inline" novalidate>
					<div class="form-inline" style="display: inline-block;">
						<div class="form-group">
							<label>Show</label>
							<select class="form-control  input-sm" v-model="per_page">
								<option v-for="option in perPageOptions" :value="option">{{option}}</option>
							</select>
						</div>
					</div>
					<div class="input-group input-group-sm">
						<input type="text" class="form-control" v-model="search" debounce="250" placeholder="Search for anything">
						<span class="input-group-addon"><i class="fa fa-search"></i></span>
					</div>
					<export-utility url="campaigns/export"
              :options="exportOptions"
              :filters="exportFilters">
          </export-utility>
          <!-- <import-utility title="Import Campaigns List" 
              url="campaigns/import" 
              :required-fields="importRequiredFields" 
              :optional-fields="importOptionalFields">
          </import-utility> -->
				</form>
			</div>
		</div>
		<hr class="divider sm">
		<div style="position:relative;">
			<spinner v-ref:spinner size="sm" text="Loading"></spinner>
			<table class="table table-striped">
				<thead>
				<tr>
					<th>Country</th>
					<th>Dates</th>
					<th>Name</th>
					<th>Groups</th>
					<th>Status</th>
					<th>Manage</th>
				</tr>
				</thead>
				<tbody>
				<tr v-for="campaign in campaigns">
					<td>{{ campaign.country }}</td>
					<td>{{ campaign.started_at|moment 'MM DD, YYYY'}} - {{ campaign.ended_at|moment 'MM DD, YYYY' }}</td>
					<td>{{ campaign.name }}</td>
					<td>{{ campaign.groups.data.length }} <i class="fa fa-group"></i></td>
					<td>
						<i class="fa" :class="{ 'fa-calendar':campaign.status === 'Scheduled', 'fa-check':campaign.pencil === 'Draft', 'fa-check':campaign.status === 'Published' }"></i> {{ campaign.status }}
					</td>
					<td class="text-center"><a href="/admin/campaigns/{{ campaign.id }}"><i class="fa fa-gear"></i></a>
					</td>
				</tr>
				</tbody>
				<tfoot>
				<tr>
					<td colspan="10" class="text-center">
						<pagination :pagination.sync="pagination" :callback="searchCampaigns"></pagination>
					</td>
				</tr>
				</tfoot>
			</table>
		</div>
	</div>
</template>
<script type="text/javascript">
    import vSelect from "vue-select";
    import exportUtility from '../export-utility.vue';
  	import importUtility from '../import-utility.vue';
    export default{
    	name: 'admin-campaigns-list',
        components: {vSelect, exportUtility, importUtility},
		props: {
			type: {
				type: String,
				default: 'current'
			}
		},
    data(){
        return{
            campaigns: [],
            orderByField: 'surname',
            direction: 1,
            page: 1,
            per_page: 10,
            perPageOptions: [5, 10, 25, 50, 100],
            pagination: {current_page: 1},
            search: '',
            exportOptions: {
            	id: 'Campaign ID',
		          name: 'Name',
              country: 'Country',
		          country_code: 'Country Code',
              groups: 'Groups',
              fund: 'Fund',
		          short_desc: 'Short Description',
              url: 'Page URL',
		          page_filename: 'Page Filename',
		          avatar_filename: 'Avatar Filename',
		          banner_filename: 'Banner Filename',
		          started_at: 'Started',
		          ended_at: 'Ended',
		          published_at: 'Published',
		          created_at: 'Created On',
		          updated_at: 'Last Updated'
		        },
		        exportFilters: {},
		        importRequiredFields: [
		          'name', 'country_code', 'started_at', 'ended_at'
		        ],
		        importOptionalFields: [
		          'short_desc', 'page_filename', 'avatar_filename', 'banner_filename',
		          'started_at', 'ended_at', 'published_at', 'created_at', 'updated_at',
                  'url'
		        ],
				filters: {}
        }
    },
		watch: {
			'per_page': function (val, oldVal) {
				this.searchCampaigns();
			},
			'search': function (val, oldVal) {
				this.pagination.current_page = 1;
				this.searchCampaigns();
			},

		},
        methods: {
			searchCampaigns(){
				let params = {
				    include: 'groups',
					search: this.search,
					per_page: this.per_page,
					page: this.pagination.current_page,
					sort: this.orderByField + '|' + (this.direction === 1 ? 'asc' : 'desc')
				};

				switch (this.type) {
					case 'current':
						params.current = true;
						break;
					case 'archived':
						params.archived = true;
						break;
				}

				$.extend(params, this.filters);
        this.exportFilters = params;

				this.$http.get('campaigns', { params: params }).then(function (response) {
					this.campaigns = response.body.data;
					this.pagination = response.body.meta.pagination;
				})
			}
        },
        ready(){
            this.searchCampaigns();
        }
    }
</script>