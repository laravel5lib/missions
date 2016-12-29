<template>
    <section>
        <div class="row">
            <div class="col-xs-6">
                <h5>Travel Requirements</h5>
            </div>
            <div class="col-xs-6">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" v-model="search" debounce="250" placeholder="Search for requirements">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                </div>
            </div>
        </div>
        <hr class="divider inv">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default" v-for="requirement in requirements">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-8">
                                <h5>
                                    {{ requirement.name }}
                                    <span class="label" :class="statusLabel(requirement.status)">
                                        <i class="fa" :class="statusIcon(requirement.status)"></i>
                                        <span class="hidden-xs"> {{ requirement.status | capitalize }}</span>
                                    </span>
                                </h5>
                            </div>
                            <div class="col-xs-4 text-right">
                                <label style="margin:13px 0px;">
                                    Due {{ requirement.due_at | moment }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <document-manager :reservation-id="id"
                                          :requirement-id="requirement.id">
                        </document-manager>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
          <div class="col-xs-12 text-center">
              <pagination :pagination.sync="pagination"
                          :callback="fetch"
                          size="small">
              </pagination>
          </div>
        </div>
    </section>
</template>
<script>
    import documentManager from './document-manager.vue';
    export default{
        components: {
            documentManager,
        },
        props: {
            'id': {
                type: String,
                required: true
            }
        },
        watch: {
            'search': function (val, oldVal) {
                this.page = 1;
                this.fetch();
            },
            'page': function (val, oldVal) {
                this.fetch();
            },
            'per_page': function (val, oldVal) {
                this.fetch();
            }
        },
        data(){
            return{
                requirements: {},
                page: 1,
                per_page: 10,
                perPageOptions: [5, 10, 25, 50, 100],
                pagination: { current_page: 1 },
                search: '',
                orderByField: 'status'
            }
        },
        methods: {
            statusLabel(status) {
                switch(status) {
                    case 'complete':
                        return 'label-success';
                        break;
                    case 'reviewing':
                        return 'label-default';
                        break;
                    case 'attention':
                        return 'label-info';
                        break;
                    default:
                        return 'label-danger';
                }
            },
            statusIcon(status) {
                switch(status) {
                    case 'complete':
                        return 'fa-check';
                        break;
                    case 'reviewing':
                        return 'fa-user';
                        break;
                    case 'attention':
                        return 'fa-exclamation-triangle';
                        break;
                    default:
                        return 'fa-exclamation';
                }
            },
            fetch() {
                var params = {
                    search: this.search,
                    per_page: this.per_page,
                    page: this.pagination.current_page,
                    //sort: this.orderByField + '|' + (this.direction === 1 ? 'asc' : 'desc'),
                };

                this.$http.get('reservations/' + this.id + '/requirements', params).then(function (response) {
                    this.requirements = response.data.data
                    this.pagination = response.data.meta.pagination;
                });
            }
        },
        events: {
            'set-status': function(requirement) {
                var index = this.requirements.indexOf(_.findWhere(this.requirements, {id: requirement.id}));
                if (index !== -1) {
                  this.requirements[index].status = requirement.status;
                }
            }
        },
        ready() {
            this.fetch();
        }
    }
</script>