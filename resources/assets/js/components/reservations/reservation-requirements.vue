<template>
    <section>
        <div class="row">
            <div class="col-xs-6">
                <h5>Travel Requirements</h5>
            </div>
            <div class="col-xs-6 tour-step-search">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" v-model="search" @keyup="debouncedSearch" placeholder="Search for requirements">
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
                            <div class="col-xs-8 tour-step-status">
                                <h5>
                                    <a class="text-muted" @click="edit(requirement)" v-if="canEdit">
                                        <i class="fa fa-cog"></i>
                                    </a> {{ requirement.name }}
                                    <span class="label" :class="statusLabel(requirement.status)">
                                        <i class="fa" :class="statusIcon(requirement.status)"></i>
                                        <span class="hidden-xs"> {{ requirement.status|capitalize }}</span>
                                    </span>
                                </h5>
                            </div>
                            <div class="col-xs-4 text-right">
                                <label style="margin:13px 0px;">
                                    <span v-show="isLocked">
                                        <i class="fa fa-lock"></i> Locked &middot;
                                    </span>
                                    Due {{ requirement.due_at | moment('lll') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="alert alert-warning" v-if="! isAdminRoute && isLocked">
                            This requirement is locked and can no longer be modified.
                        </div>
                        <p>{{ requirement.short_desc }}</p>
                        <hr class="divider sm">
                    </div>
                    <div class="panel-body">
                        <document-manager :reservation-id="id"
                                          :requirement-id="requirement.id"
                                          :requirement="requirement"
                                          :user-id="userId"
                                          @set-status="handleStatus"
                                          :locked="isLocked">
                        </document-manager>
                    </div>
                    <div class="panel-footer">
                        <label>Last Updated: {{ requirement.updated_at | moment('lll') }}</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
          <div class="col-xs-12 text-center">
              <pagination :pagination="pagination"
                          :callback="fetch"
                          size="small">
              </pagination>
          </div>
        </div>

        <modal :title="'Edit ' + editedRequirement.name" :value="showEditModal" @closed="showEditModal=false" effect="fade" width="800" :callback="update">
            <div slot="modal-body" class="modal-body">

                    <form class="form" novalidate>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group" :class="{'has-error': checkForEditRequirementError('grace') }">
                                    <label for="grace_period">Grace Period</label>
                                    <div class="input-group input-group-sm" :class="{'has-error': checkForEditRequirementError('grace') }">
                                        <input id="grace_period" type="number" class="form-control" number v-model="editedRequirement.grace_period"
                                               name="grace" v-validate="'required'">
                                        <span class="input-group-addon">Days</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select v-model="editedRequirement.status" class="form-control">
                                        <option value="incomplete">Incomplete</option>
                                        <option value="reviewing">Reviewing</option>
                                        <option value="attention">Attention</option>
                                        <option value="complete">Complete</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>

            </div>
        </modal>

    </section>
</template>
<script>
    import _ from 'underscore';
    import documentManager from './document-manager.vue';
    export default{
        components: {
            documentManager,
        },
        props: {
            'id': {
                type: String,
                required: true
            },
            'age': {
                type: Number,
                default: 100
            },
            'userId': {
                type: String,
                required: true
            },
            'locked': {
                type: Number,
                default: 0
            }
        },
        computed: {
            canEdit() {
                if (this.firstUrlSegment === 'admin') return true;
                // if (_.findWhere(this.$root.user.abilities.data, {slug: "manage requirements-reservations"})) {
                //     return true;
                // }

                return false;
            },
            'isLocked': function() {
                if (this.isAdminRoute)
                    return false;

                return this.locked == 1 ? true: false;
            }
        },
        watch: {
            'search'(val, oldVal) {
                this.page = 1;
            },
            'page'(val, oldVal) {
                this.fetch();
            },
            'per_page'(val, oldVal) {
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
                orderByField: 'status',
                editedRequirement: {},
                showEditModal: false,
                attemptSubmit: false
            }
        },
        methods: {
            debouncedSearch: _.debounce(function () {
                this.fetch();
            }, 250),
            checkForEditRequirementError(field) {
                // if user clicked submit button while the field is invalid trigger error styles
                return this.$EditRequirement[field].invalid && this.attemptSubmit;
            },
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
                    include: 'document',
                    //sort: this.orderByField + '|' + (this.direction === 1 ? 'asc' : 'desc'),
                };

                this.$http.get('reservations/' + this.id + '/requirements', { params: params }).then((response) => {
                    this.requirements = response.data.data
                    this.pagination = response.data.meta.pagination;
                });
            },
            edit(requirement) {
                this.editedRequirement = requirement;
                this.showEditModal = true;
            },
            update() {
                this.$http.put('reservations/' + this.id + '/requirements/' + this.editedRequirement.id, {
                    status: this.editedRequirement.status,
                    grace_period: this.editedRequirement.grace_period
                }).then((response) => {
                    this.$emit('set-status', response.data.data);
                    this.$root.$emit('showSuccess', 'Requirement updated.');
                    this.showEditModal = false;
                });
            },
            handleStatus(requirement) {
                var index = this.requirements.indexOf(_.findWhere(this.requirements, {id: requirement.id}));
                if (index !== -1) {
                    this.requirements[index].status = requirement.status;
                    this.requirements[index].updated_at = requirement.updated_at;
                }
            }
        },
        /*events: {
            'set-status': function(requirement) {
                var index = this.requirements.indexOf(_.findWhere(this.requirements, {id: requirement.id}));
                if (index !== -1) {
                  this.requirements[index].status = requirement.status;
                  this.requirements[index].updated_at = requirement.updated_at;
                }
            }
        },*/
        mounted() {
            this.fetch();
        }
    }
</script>