<template>
<div>
    <fetch-json :url="`${requesterType}/${requesterId}/requirements`" ref="list" v-cloak>
        <div class="panel panel-default" 
             slot-scope="{ json:requirements, loading, pagination }" 
             style="border-top: 5px solid #f6323e"
        >
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-8">
                        <h5>Travel Requirements <span class="badge">{{ pagination.total }}</span></h5>
                    </div>
                    <div class="col-xs-4 text-right">
                        <div class="btn-group btn-group-sm" v-if="inheritableId">
                            <button type="button" 
                                    class="btn btn-primary dropdown-toggle" 
                                    data-toggle="dropdown" 
                                    aria-haspopup="true" 
                                    aria-expanded="false"
                            >
                                Add New <span class="caret"></span>
                          </button>
                          <ul class="dropdown-menu dropdown-menu-right">
                            <li>
                                <a role="button" 
                                   data-toggle="modal" 
                                   data-target="#addRequirementModal"
                                >
                                    {{ inheritableLabel | capitalize }} Requirement
                                </a>
                            </li>
                            <li role="separator" class="divider"></li>
                            <li>
                                <a :href="`/admin/${requesterType}/${requesterId}/requirements/create`">
                                    Custom Requirement
                                </a>
                            </li>
                          </ul>
                        </div>
                        <a :href="`/admin/${requesterType}/${requesterId}/requirements/create`" 
                           class="btn btn-sm btn-primary"
                           v-else
                        >
                            Add New
                        </a>
                    </div>
                </div>
            </div>
            <div class="panel-body text-center" v-if="!loading && !requirements.length">
                <span class="lead">No Requirements</span>
                <p>Add a requirement to get started.</p>
            </div>
            <div class="table-responsive" v-else>
                <table class="table table-striped">
                    <thead>
                        <tr class="active">
                            <th>#</th>
                            <th>Requirement</th>
                            <th class="text-right" 
                                v-if="requesterType === 'campaigns'"
                            >
                                Groups
                            </th>
                            <th class="text-right" 
                                v-if="requesterType === 'campaign-groups' || requesterType === 'campaigns'"
                            >
                                Trips
                            </th>
                            <th class="text-right" v-if="requesterType != 'reservations'">Reservations</th>
                            <th v-else>Status</th>
                            <th>Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(requirement, index) in requirements">
                            <td class="text-muted">{{ index + 1 }}</td>
                            <td>
                                <strong>
                                    <a :href="`/admin/${requesterType}/${requesterId}/requirements/${requirement.id}`">
                                        {{ requirement.name }} 
                                        <span class="label" 
                                              style="background:#eee; color:#777" 
                                              v-if="requirement.custom">
                                          Custom
                                        </span>
                                    </a>
                                </strong>
                            </td>
                            <td class="text-right" 
                                v-if="requesterType === 'campaigns'"
                            >
                                <code>{{ requirement.groups_count }}</code>
                            </td>
                            <td class="text-right" 
                                v-if="requesterType === 'campaigns' || requesterType === 'campaign-groups'"
                            >
                                <code>{{ requirement.trips_count }}</code>
                            </td>
                            <td class="text-right" v-if="requesterType != 'reservations'">
                                <code>{{ requirement.reservations_count }}</code>
                            </td>
                            <td v-else v-html="getStatusLabel(requirement.status)"></td>
                            <td>{{ requirement.updated_at | moment('lll', true) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </fetch-json>

    <div class="modal fade" 
         tabindex="-1" 
         role="dialog" 
         id="addRequirementModal"
         v-if="inheritableId"
    >
        <div class="modal-dialog" role="document">
            <ajax-form :action="`${requesterType}/${requesterId}/requirements`" 
                       method="post" 
                       :initial="{requirement_id: null}"
                       @form:success="updateList"
            >
            <div class="modal-content" slot-scope="{ form }">
                <div class="modal-header">
                    <button type="button" 
                            class="close" 
                            data-dismiss="modal" 
                            aria-label="Close"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Add a {{ inheritableLabel | capitalize }} Requirement</h4>
                </div>
                <div class="modal-body">
                    <input-select 
                        name="requirement_id" 
                        v-model="form.requirement_id" 
                        :options="requirements"
                    >
                        <label slot="label">Requirement</label>
                    </input-select>
                </div>
                <div class="modal-footer">
                    <button type="button" 
                            class="btn btn-default" 
                            data-dismiss="modal"
                    >
                        Close
                    </button>
                    <button type="submit" 
                            class="btn btn-primary"
                    >
                        Add
                    </button>
                </div>
            </div>
            </ajax-form>
        </div>
    </div>

    <alert-error>
        <template slot="title">Oops!</template>
        <template slot="message">Please check the form for errors and try again.</template>
    </alert-error>

    <alert-success :timer="1000" :reload="true">
        <template slot="title">Nice Work!</template>
        <template slot="message">A new requirement was added.</template>
    </alert-success>

</div>
</template>

<script>
export default {

    props: {
        inheritableId: String,
        inheritableType: String,
        requesterType: {
            type: String,
            required: true
        },
        requesterId: {
            type: String,
            required: true
        }
    },

    data () {
        return {
            requirements: null
        }
    },

    computed: {
        inheritableLabel() {
            if (this.inheritableType === 'campaign-groups') {
                return 'group';
            }
            
            return this.inheritableType.slice(0, -1);
        }
    },

    methods: {
        fetchInheritableRequirements() {
            this.$http.get(`${this.inheritableType}/${this.inheritableId}/requirements`).then((response) => {

                let requirements = {'': 'Select a requirement'};
                
                _.map(response.data.data, function (requirement) {
                    requirements[requirement.id] = requirement.name;
                });

                this.requirements = requirements;

            }).catch((error) => {
                swal('Oops!', 'Something went wrong!', 'error');
            });
        },
        updateList() {
            $('#addRequirementModal').modal('hide');
            this.$refs.list.fetch();
        },
        getStatusLabel(status)
        {
            let labels = {
                'incomplete': '<span class="badge badge-error">Incomplete</span>',
                'attention': '<span class="badge badge-info">Needs Attention</span>',
                'reviewing': '<span class="badge badge-muted">Under Review</span>',
                'complete': '<span class="badge badge-success">Completed</span>'
            };

            return labels[status];
        }
    },

    created() {
        if (this.inheritableId) {
            this.fetchInheritableRequirements();
        }
    }
}
</script>