<template>
    <div class="row" style="position:relative">
        <spinner v-ref:spinner size="sm" text="Loading"></spinner>
        <div class="col-xs-12 text-right">
            <form class="form-inline">
                <div style="margin-right:5px;" class="checkbox">
                    <label>
                        <input type="checkbox" v-model="includeManaging"> Include my group's essays
                    </label>
                </div>
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" v-model="search" debounce="250" placeholder="Search">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                </div>
                <!--<button class="btn btn-default btn-sm" type="button" @click="showFilters=!showFilters">
                    Filters
                    <i class="fa fa-filter"></i>
                </button>-->
            </form>
            <hr class="divider sm inv">
        </div>

        <div class="col-sm-12" v-if="loaded && !essays.length">
            <p class="text-center text-muted" role="alert"><em>No records found</em></p>
        </div>

        <div class="col-md-4 col-sm-6" v-for="essay in essays">
            <div class="panel panel-default">
                <div class="panel-body">
                    <label>{{essay.subject}}</label>
                    <a role="button" :href="'/dashboard/records/essays/' + essay.id">
                        <h5 class="text-primary text-capitalize" style="margin-top:0px;margin-bottom:5px;">
                            {{essay.author_name}}
                        </h5>
                    </a>
                    <div style="position:absolute;right:20px;top:5px;">
                        <a style="margin-right:3px;" :href="'/dashboard/records/essays/' + essay.id + '/edit'"><i class="fa fa-pencil"></i></a> 
                        <a @click="selectedEssay = essay, deleteModal = true"><i class="fa fa-times"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 text-center">
            <pagination :pagination.sync="pagination" :callback="searchEssays"></pagination>
        </div>
        <modal :show.sync="deleteModal" title="Remove Essay" small="true">
            <div slot="modal-body" class="modal-body text-center">Are you sure you want to delete this Essay?</div>
            <div slot="modal-footer" claass="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Exit</button>
                <button type="button" class="btn btn-primary btn-sm" @click='deleteModal = false,removeEssay(selectedEssay)'>Confirm</button>
            </div>
        </modal>
    </div>
</template>
<script type="text/javascript">
    export default{
        name: 'essays-list',
        props: {
            'userId': {
                type: String,
                required: true
            }
        },
        data(){
            return{
                essays: [],
                selectedEssay: '',
                //logic vars
                includeManaging: false,
                search: '',
                per_page: 3,
                pagination: {
                    current_page:1,
                },
                loaded: false,
                deleteModal: false,
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
