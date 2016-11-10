<template>
    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-6">
                            <h5>Details</h5>
                        </div>
                        <div class="col-xs-6 text-right" v-if="!editMode">
                            <button class="btn btn-xs btn-default-hollow" @click="editMode = true">
                                <i class="fa fa-pencil"></i> Edit
                            </button>
                        </div>
                        <div class="col-xs-6 text-right" v-else>
                            <button class="btn btn-xs btn-default" @click="cancel">
                                Cancel
                            </button>
                            <button class="btn btn-xs btn-primary" @click="save">
                                <i class="fa fa-save"></i> Save
                            </button>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <label>Name</label>
                    <input class="form-control" v-model="type.name" v-if="editMode" />
                    <p v-else>{{ type.name }}</p>
                    <label>Country</label>
                    <v-select class="form-control"
                              id="country"
                              :value.sync="type.country"
                              :options="countries"
                              label="name"
                              v-if="editMode">
                    </v-select>
                    <p v-else>
                        <span class="label label-default">
                            {{ type.country.name }}
                        </span>
                    </p>
                    <label>Description</label>
                    <textarea class="form-control" v-model="type.short_desc" v-if="editMode" rows="10"></textarea>
                    <p v-else>{{ type.short_desc }}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-6">
                            <h5>Pricing</h5>
                        </div>
                        <div class="col-xs-6 text-right" v-if="!editMode">
                            <button class="btn btn-xs btn-default-hollow" @click="editMode = true">
                                <i class="fa fa-pencil"></i> Edit
                            </button>
                        </div>
                        <div class="col-xs-6 text-right" v-else>
                            <button class="btn btn-xs btn-default" @click="cancel">
                                Cancel
                            </button>
                            <button class="btn btn-xs btn-primary" @click="save">
                                <i class="fa fa-save"></i> Save
                            </button>
                        </div>
                    </div>
                </div>
                <div class="panel-body" v-for="cost in type.costs">
                    <h4>{{ cost.name }}</h4>
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="small">{{ cost.description }}</p>
                        </div>
                    </div>
                    <hr class="divider">
                    <div class="row">
                        <div class="col-sm-4 text-center">
                            <label>Cost Type</label>
                            <p>{{ cost.type }}</p>
                        </div>
                        <div class="col-sm-4 text-center">
                            <label>Active Date</label>
                            <p>{{ cost.active_at | moment 'll' }}</p>
                        </div>
                        <div class="col-sm-4 text-center">
                            <label>Cost</label>
                            <p>{{ cost.amount | currency }}</p>
                        </div>
                    </div>
                    <hr class="divider">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Amount</th>
                            <th>Percent</th>
                            <th>Due</th>
                            <th>Grace</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="payment in cost.payments">
                            <td>{{ payment.amount_owed | currency }}</td>
                            <td>{{ payment.percent_owed }}%</td>
                            <td v-if='payment.upfront'>Upfront</td>
                            <td v-else>{{ payment.due_at | moment 'll' }}</td>
                            <td v-if='payment.upfront'>N/A</td>
                            <td v-else>{{ payment.grace_period }} {{ payment.grace_period | pluralize 'day' }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <alert :show.sync="showSuccess"
               placement="top-right"
               :duration="3000"
               type="success"
               width="400px"
               dismissable>
            <span class="icon-ok-circled alert-icon-float-left"></span>
            <strong>Well Done!</strong>
            <p>{{ message }}</p>
        </alert>

        <alert :show.sync="showError"
               placement="top-right"
               :duration="6000"
               type="danger"
               width="400px"
               dismissable>
            <span class="icon-info-circled alert-icon-float-left"></span>
            <strong>Oh No!</strong>
            <p>{{ message }}</p>
        </alert>

    </div>
</template>
<script>
    import VueStrap from 'vue-strap/dist/vue-strap.min';
    import vSelect from 'vue-select';
    export default{
        name: 'project-type-editor',
        data() {
            return {
                type: {
                    country: {}
                },
                countries: [],
                editMode: false,
                showSuccess: false,
                showError: false,
                message: ''
            }
        },
        props: {
            'id': {
                type: String,
                required: true
            },
            'causeId': {
                type: String,
                required: true
            }
        },
        components: {
            'alert': VueStrap.alert,
            'v-select': vSelect
        },
        methods: {
            fetch() {
                this.$http.get('types/' + this.id + '?include=costs.payments').then(function (response) {
                    var type = response.data.data;
                    _.each(type.costs.data, function (cost) {
                        cost.payments = cost.payments.data;
                    });
                    type.costs = type.costs.data;

                    this.type = type;
                });
            },
            save() {
                this.type.country_code = this.type.country.code;
                this.$http.put('types/' + this.id, this.type).then(function (response) {
                    this.editMode = false;
                    this.message = 'Your changes were saved successfully.';
                    this.showSuccess = true;
                }).error(function () {
                    this.message = 'Your changes could not be saved.';
                    this.showError = true;
                });
            },
            cancel() {
                this.fetch();
                this.editMode = false;
            }
        },
        ready() {
            this.fetch();

            this.$http.get('causes/' + this.causeId).then(function (response) {
				this.countries = response.data.data.countries;
			});
        }
    }
</script>