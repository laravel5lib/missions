<template>
    <div class="row" style="position:relative">
        <spinner v-ref:spinner size="sm" text="Loading"></spinner>
        <div class="col-xs-12 text-right">
            <form class="form-inline">
                <div style="margin-right:5px;" class="checkbox" v-if="userId">
                    <label>
                        <input type="checkbox" v-model="includeManaging"> Include my group's essays
                    </label>
                </div>
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" v-model="search" debounce="250" placeholder="Search">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                </div>
                <export-utility v-if="canExport" url="essays/export"
                                :options="exportOptions"
                                :filters="exportFilters">
                </export-utility>
                <import-utility title="Import Essays List" 
                      url="essays/import" 
                      :required-fields="importRequiredFields" 
                      :optional-fields="importOptionalFields">
                </import-utility>
            </form>
            <hr class="divider sm inv">
        </div>

        <div class="col-sm-12" v-if="loaded && !essays.length">
            <p class="text-center text-muted" role="alert"><em>Add and manage your essays here!</em></p>
        </div>

        <div class="col-md-4 col-sm-6" v-for="essay in essays">
            <div class="panel panel-default">
                <div class="panel-body">
                    <a role="button" :href="'/'+ firstUrlSegment +'/records/essays/' + essay.id">
                        <h5 class="text-primary text-capitalize" style="margin-top:0px;margin-bottom:5px;">
                            {{essay.author_name}}
                        </h5>
                    </a>
                    <hr class="divider">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>SUBJECT</label>
                            <p class="small">{{essay.subject}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label>QUESTIONS:</label>
                            <p class="small">{{essay.content.length}}</p>
                        </div>
                        <div class="col-sm-6">
                            <label>UPDATED:</label>
                            <p class="small">{{essay.updated_at|moment 'll'}}</p>
                        </div>
                    </div>
                    <div style="position:absolute;right:20px;top:5px;">
                        <a style="margin-right:3px;" :href="'/'+ firstUrlSegment +'/records/essays/' + essay.id + '/edit'"><i class="fa fa-pencil"></i></a>
                        <a @click="selectedEssay = essay, deleteModal = true"><i class="fa fa-times"></i></a>
                    </div>
                </div>
                <div class="panel-footer" style="padding: 0;" v-if="selector">
                    <div class="btn-group btn-group-justified btn-group-sm" role="group" aria-label="...">
                        <a class="btn btn-danger" @click="setEssay(essay)">
                            Select
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 text-center">
            <pagination :pagination.sync="pagination" :callback="searchEssays"></pagination>
        </div>
        <modal :show.sync="deleteModal" title="Remove Essay" small="true">
            <div slot="modal-body" class="modal-body text-center">Delete this Essay?</div>
            <div slot="modal-footer" claass="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Keep</button>
                <button type="button" class="btn btn-primary btn-sm" @click='deleteModal = false,removeEssay(selectedEssay)'>Delete</button>
            </div>
        </modal>
    </div>
</template>
<script type="text/javascript">
    import exportUtility from '../../export-utility.vue';
    import importUtility from '../../import-utility.vue';
    export default{
        name: 'essays-list',
        components: {exportUtility, importUtility},
        props: {
            'userId': {
                type: String,
                required: false
            },
            'selector': {
                type: Boolean,
                default: false
            }
        },
        data(){
            return{
                essays: [],
                selectedEssay: '',
                //logic vars
                includeManaging: false,
                search: '',
                per_page: 15,
                pagination: {
                    current_page:1,
                },
                loaded: false,
                deleteModal: false,
                exportOptions: {
                    id: 'ID',
                    author_name: 'Author Name',
                    subject: 'Subject',
                    created_at: 'Created On',
                    updated_at: 'Last Updated',
                },
                exportFilters: {},
                importRequiredFields: [
                    'author_name', 'user_email', 'subject', 'content'
                ],
                importOptionalFields: [
                    'created_at', 'updated_at'
                ],
            }
        },
        watch:{
            'search': function (val, oldVal) {
                this.pagination.current_page = 1;
                this.searchEssays();
            },
            'includeManaging': function (val, oldVal) {
                this.pagination.current_page = 1;
                this.searchEssays();
            }
        },
        methods:{
            canExport() {
                let roles = _.pluck(this.$root.user.roles.data, 'name');
                return !!this.$root.user ? _.contains(roles, 'admin') : false;
                // TODO - use abilities instead of roles
                // return this.$root.hasAbility('') ||  this.$root.hasAbility('') ||  this.$root.hasAbility('');
            },
            setEssay(essay) {
                this.$dispatch('set-document', essay);
            },
            removeEssay(essay){
                if(essay) {
                    this.$http.delete('essays/' + essay.id).then(function (response) {
                        this.essays = _.reject(this.essays, function (item) {
                            return item.id === essay.id;
                        });
                        this.selectedEssay = '';
                    });
                }
            },
            searchEssays(){
                let params = {user: this.userId, sort: 'author_name', search: this.search, per_page: this.per_page, page: this.pagination.current_page};
                if (this.includeManaging)
                    params.manager = this.userId;
                this.exportFilters = params;
                this.$http.get('essays', params).then(function (response) {
                    this.essays = response.data.data;
                    this.pagination = response.data.meta.pagination;
                    this.loaded = true;
                    // this.$refs.spinner.hide();
                });
            }
        },
        ready(){
            this.searchEssays();
        }
    }
</script>
