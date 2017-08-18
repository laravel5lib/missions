<template>
    <div>
        <mm-aside :show="showFilters" @open="showFilters=true" @close="showFilters=false" placement="left" header="Filters" :width="375">
            <hr class="divider inv sm">
            <form class="col-sm-12">
                <div class="form-group">
                    <select class="form-control" v-model="filters.type" style="width:100%;">
                        <option value="">Any Type</option>
                        <option value="avatar">Avatar</option>
                        <option value="banner">Banner</option>
                        <option value="file">File</option>
                        <option value="photo">Photo</option>
                        <option value="thumbnail">Thumbnail</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control input-sm" style="width:100%" v-model="tagsString"
                           :debounce="250" placeholder="Tag, tag2, tag3...">
                </div>

                <hr class="divider inv sm">
                <button class="btn btn-default btn-sm btn-block" type="button" @click="resetFilter()"><i class="fa fa-times"></i> Reset Filters</button>
            </form>
        </mm-aside>
        <div class="row">
            <div class="col-sm-12">
                <form class="form-inline" novalidate>
                    <div class="form-inline" style="display: inline-block;">
                        <div class="form-group">
                            <label>Show</label>
                            <select class="form-control input-sm" v-model="per_page">
                                <option v-for="option in perPageOptions" :value="option">{{option}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" v-model="search" debounce="250" placeholder="Search for anything">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                    </div>
                    <div id="toggleFilters" class="form-toggle-menu dropdown" style="display: inline-block;">
                        <button class="btn btn-default btn-sm dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Sort By
                        <span class="caret"></span>
                        </button>
						<ul style="padding: 10px 20px;" class="dropdown-menu" aria-labelledby="dropdownMenu2">
							<li>
								<select class="form-control" v-model="filters.type" style="width:100%;">
									<option value="">Any Type</option>
									<option value="avatar">Avatar</option>
									<option value="banner">Banner</option>
									<option value="file">File</option>
									<option value="photo">Photo</option>
									<option value="thumbnail">Thumbnail</option>
								</select>
							</li>

							<li role="separator" class="divider"></li>
							<button class="btn btn-default btn-sm btn-block" type="button" @click="resetFilter()">Reset Filters</button>
						</ul>
                    </div>
                    <button class="btn btn-default btn-sm" type="button" @click="showFilters=!showFilters">
                        Filters
                        <span class="caret"></span>
                    </button>
                </form>
            </div>
        </div>
        <hr>
        <div style="position:relative">
            <spinner ref="spinner" size="sm" text="Loading"></spinner>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Preview</th>
                    <th :class="{'text-primary': orderByField === 'name'}">
                        Name
                        <i @click="setOrderByField('name')" v-if="orderByField !== 'name'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=!direction" v-if="orderByField === 'name'" class="fa pull-right" :class="{'fa-sort-desc': direction, 'fa-sort-asc': !direction}"></i>
                    </th>
                    <th :class="{'text-primary': orderByField === 'type'}">
                        Type
                        <i @click="setOrderByField('type')" v-if="orderByField !== 'type'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=!direction" v-if="orderByField === 'type'" class="fa pull-right" :class="{'fa-sort-desc': direction, 'fa-sort-asc': !direction}"></i>
                    </th>
                    <th>Tags</th>
                    <th :class="{'text-primary': orderByField === 'created_at'}">
                        Created
                        <i @click="setOrderByField('created_at')" v-if="orderByField !== 'created_at'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=!direction" v-if="orderByField === 'created_at'" class="fa pull-right" :class="{'fa-sort-desc': direction, 'fa-sort-asc': !direction}"></i>
                    </th>
                    <th :class="{'text-primary': orderByField === 'updated_at'}">
                        Updated
                        <i @click="setOrderByField('updated_at')" v-if="orderByField !== 'updated_at'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=!direction" v-if="orderByField === 'updated_at'" class="fa pull-right" :class="{'fa-sort-desc': direction, 'fa-sort-asc': !direction}"></i>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="upload in uploads">
                    <td><img v-if="upload.type !== 'file'" :src="checkSource(upload.source)" width="100px"/></td>
                    <td v-text="upload.name|capitalize"></td>
                    <td v-text="upload.type|capitalize"></td>
                    <td v-text="upload.tags|capitalize"></td>
                    <td v-text="upload.created_at|moment('ll')"></td>
                    <td v-text="upload.updated_at|moment('ll')"></td>
                    <td class="text-center"><a :href="'/admin' + upload.links[0].uri + '/edit'"><i class="fa fa-gear"></i></a></td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="7">
                        <div class="col-sm-12 text-center">
                            <pagination :pagination="pagination" :callback="searchUploads"></pagination>
                        </div>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</template>
<style>
	#toggleFilters li {
		margin-bottom: 3px;
	}
</style>
<script type="text/javascript">
    import $ from 'jquery';
	import vSelect from "vue-select";
	export default{
        name: 'admin-uploads-list',
		components: {vSelect},
		data(){
            return{
                uploads: [],
                orderByField: 'name',
                direction: 1,
                page: 1,
                per_page: 10,
                tagsString: '',
                perPageOptions: [5, 10, 25, 50, 100],
                pagination: { current_page: 1 },
                search: '',
				// filter vars
                showFilters: false,
				filters: {
					type:'',
					tags: [],
				}
            }
        },
		computed: {
		},
        watch: {
			// watch filters obj
			'filters': {
				handler(val, oldVal) {
					// console.log(val);
                    this.pagination.current_page = 1;
                    this.searchUploads();
				},
				deep: true
			},
            'search'(val, oldVal) {
                this.pagination.current_page = 1;
                this.searchUploads();
            },
            'orderByField'(val, oldVal) {
				this.searchUploads();
            },
            'direction'(val, oldVal) {
				this.searchUploads();
            },
            'tagsString'(val, oldVal) {
                let tags = val.split(/[\s,]+/);
                this.filters.tags = tags[0] !== '' ? tags : '';
                this.searchUploads();
            },
            'page'(val, oldVal) {
				this.searchUploads();
            },
            'per_page'(val, oldVal) {
				this.searchUploads();
            },
        },
        methods: {
            checkSource(link){
//                return decodeURIComponent(link.indexOf('/http') ? 'http' + link.split('/http')[1]: link);
                return link + '?w=100&q=25';
            },
            setOrderByField(field){
                return this.orderByField = field, this.direction = 1;
            },
            resetFilter(){
                this.orderByField = 'name';
                this.direction = 1;
                this.search = null;
				this.countriesArr = [];
				this.filters =  {
					type:'',
					tags: [],
				}
			},
            searchUploads(){
            	let params = {
					include: '',
					search: this.search,
					per_page: this.per_page,
					page: this.pagination.current_page,
					sort: this.orderByField + '|' + (this.direction?'asc':'desc'),
				};

				$.extend(params, this.filters);
                return this.$http.get('uploads', { params: params }).then((response) => {
                    this.pagination = response.data.meta.pagination;
                    this.uploads = response.data.data;
                }, this.$root.handleApiErrors);
            },
        },
        mounted(){
			// populate
            this.searchUploads();

			//Manually handle dropdown functionality to keep dropdown open until finished
			$('.form-toggle-menu .dropdown-menu').on('click', function(event){
				let events = $._data(document, 'events') || {};
				events = events.click || [];
				for(let i = 0; i < events.length; i++) {
					if(events[i].selector) {

						//Check if the clicked element matches the event selector
						if($(event.target).is(events[i].selector)) {
							events[i].handler.call(event.target, event);
						}

						// Check if any of the clicked element parents matches the
						// delegated event selector (Emulating propagation)
						$(event.target).parents(events[i].selector).each(function(){
							events[i].handler.call(this, event);
						});
					}
				}
				event.stopPropagation(); //Always stop propagation
			});
        }
    }
</script>
