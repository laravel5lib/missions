<template>
    <div class="panel panel-default">
        <spinner ref="spinner" size="sm" text="Loading"></spinner>

        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-6">
                    <h5>Details</h5>
                </div>
                <div class="col-xs-6 text-right">
                    <button class="btn btn-xs btn-default-hollow"
                            @click="editMode = !editMode"
                            v-if="!editMode">
                        Edit
                    </button>
                    <button class="btn btn-xs btn-default-hollow"
                            @click="editMode = !editMode"
                            v-if="editMode">
                        Cancel
                    </button>
                    <button class="btn btn-xs btn-primary"
                            @click="save"
                            v-if="editMode">
                        Save
                    </button>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-6">
                    <label>Name</label>
                    <input class="form-control" v-model="interest.name" v-if="editMode">
                    <p v-else>{{ interest.name }}</p>
                </div>
                <div class="col-sm-6">
                    <label>Status</label>
                    <select class="form-control" v-model="interest.status" v-if="editMode">
                        <option value="undecided">Undecided</option>
                        <option value="converted">Converted</option>
                        <option value="declined">Declined</option>
                    </select>
                    <p v-else>
                        <span class="label"
                              :class="{
                              'label-danger': interest.status == 'undecided',
                              'label-success': interest.status == 'converted',
                              'label-default': interest.status == 'declined'
                              }">
                            {{ interest.status|capitalize }}
                        </span>
                    </p>
                </div>
            </div>
            <hr class="divider">
            <div class="row">
                <div class="col-sm-6">
                    <label>Email</label>
                    <input class="form-control" v-model="interest.email" v-if="editMode">
                    <p v-else>{{ interest.email }}</p>
                </div>
                <div class="col-sm-6">
                    <label>Phone</label>
                    <input class="form-control" v-model="interest.phone" v-if="editMode">
                    <p v-else>{{ interest.phone }}</p>
                </div>
            </div>
            <hr class="divider">
            <div class="row">
                <div class="col-sm-6">
                    <label>Trip of Interest</label>
                    <p v-if="interest.trip">
                        {{ interest.trip.campaign.name }} <br />
                        <small class="text-muted">
                            {{ interest.trip.group.name }} <br />
                            <span class="label label-default">{{ interest.trip.type|capitalize }}</span>
                        </small>
                    </p>
                </div>
                <div class="col-sm-6">
                    <label>Communication Preferences</label>
                    <h5>
                        <span class="label label-default" style="margin-right:5px" v-for="preference in interest.communication_preferences">
                            {{ preference|capitalize }}
                        </span>
                    </h5>
                </div>
            </div>
            <hr class="divider">
            <div class="row">
                <div class="col-sm-6">
                    <label>Received at</label>
                    <p>{{ interest.created_at | moment('lll') }}</p>
                </div>
                <div class="col-sm-6">
                    <label>Last Updated at</label>
                    <p>{{ interest.updated_at | moment('lll') }}</p>
                </div>
            </div>
        </div>

        <alert v-model="showSuccess" placement="top-right" :duration="3000" type="success" width="400px" dismissable>
            <span class="icon-ok-circled alert-icon-float-left"></span>
            <strong>Done</strong>
            <p>{{ message }}</p>
        </alert>

    </div>
</template>
<script type="text/javascript">
    export default{
        data() {
            return {
                interest: {},
                showSuccess: false,
                message: '',
                editMode: false
            }
        },
        props: {
            'id': {
                type: String,
                required: true
            }
        },
        methods: {
            fetch() {
                // this.$refs.spinner.show();
                this.$http.get('interests/' + this.id, { params: {
                    include: 'trip.campaign,trip.group'
                }}).then((response) => {
                    let interest = response.data.data;
                    interest.trip = interest.trip.data;
                    interest.trip.campaign = interest.trip.campaign.data;
                    interest.trip.group = interest.trip.group.data;
                    this.interest = interest;
                    // this.$refs.spinner.hide();
                })
            },
            save() {
                // this.$refs.spinner.show();
                this.$http.put('interests/' + this.id, this.interest).then((response) => {
                    this.message = 'Trip interest updated';
                    this.showSuccess = true;
                    this.editMode = false;
                    this.fetch();
                });
            },
        },
        mounted() {
            this.fetch();
        }
    }
</script>