<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <spinner ref="spinner" size="sm" text="Loading"></spinner>
                <div class="col-sm-8">
                    <h5 v-if="id">Edit Promotional</h5>
                    <h5 v-else>Create New Promotional</h5>
                </div>
                <div class="col-sm-4 text-right">
                    <button @click="callView(url)" class="btn btn-primary btn-sm">
                        <i class="fa fa-chevron-left"></i> Back
                    </button>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <form id="ModifyPromotional" novalidate>
            <div class="row">
                <div class="form-group col-sm-6" 
                     v-error-handler="{ value: promo.name, client: 'name', server: 'name', messages: { req: 'Please provide a name at least 3 characters long.', min: 'Please provide a name at least 3 characters long.', max: 'Please provide a name less than 100 characters long.'} }">

                    <label>Promotional Name</label>
                    <input type="text" v-model="promo.name" class="form-control"
                           name="name" v-validate="'required|min:3|max:100'">
                </div>

                <div class="form-group col-sm-6" v-if="!id">
                    <label>Referral Affiliates</label>
                    <select v-model="promo.affiliates" class="form-control">
                        <option :value="null">-- no affiliates --</option>
                        <option value="reservations">Reservations</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-6" 
                     v-error-handler="{ value: promo.reward, client: 'reward', server: 'reward', messages: { req: 'Please provide a credit amount of $1.00 or more.'} }">
                    <label>Credit Amount</label>
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="number" 
                               v-model="promo.reward" 
                               class="form-control" 
                               name="reward" v-validate="'required|min:1'"
                               required>
                        <span class="input-group-addon">.00</span>
                    </div>
                    <span class="help-block" v-if="id">This will not change credits already applied.</span>
                </div>

                <div class="form-group col-sm-6">
                    <label>Expires</label>
                    <date-picker v-model="promo.expires_at" :view-format="['YYYY-MM-DD', false, true]"></date-picker>
                </div>
            </div>
            </form>
        </div>
        <div class="panel-body text-right">
            <button class="btn btn-default" @click="cancel">Cancel</button>
            <button class="btn btn-primary" @click="update" v-if="id">Update</button>
            <button class="btn btn-primary" @click="create" v-else>Create</button>
        </div>
    </div>
</template>
<script type="text/javascript">
    import errorHandler from'../../error-handler.mixin';
    export default {
        name: 'create-edit',
        props: {
          'id': {
            type: String,
            default: null
          },
          'promoterType': {
            type: String,
            required: true
          },
          'promoterId': {
            type: String,
            required: true
          }
        },
        mixins: [errorHandler],
        data() {
            return {
                promo: {
                    name: null,
                    reward: 0,
                    expires_at: null,
                    affiliates: null
                },
            }
        },
        computed: {
            url() {
                return this.id ? {view: 'details', id: this.id} : {view: 'list'};
            }
        },
        events: {},
        methods: {
            fetch() {
                this.$http.get('promotionals/' + this.id).then((response) => {
                    let promo = response.data.data;
                    promo.reward = parseInt(promo.reward.replace(/,/, ''));
                    this.promo = promo;
                }, (error) =>  {
                    this.$root.$emit('showError', 'Unable to get data from server.');
                })
            },
            create() {
                this.$validator.validateAll().then(result => {
                    if (result) {
                        $.extend(this.promo, {promoteable_id: this.promoterId, promoteable_type: this.promoterType});
                        this.$http.post('promotionals', this.promo).then((response) => {
                            this.$root.$emit('showSuccess', 'Promotional created.');
                            this.$emit('load-view', {view: 'details', id: response.data.data.id});
                        }, (error) => {
                            this.$root.$emit('showError', 'Could not create promotional.');
                        })
                    }
                })
            },
            update() {
                this.$validator.validateAll().then(result => {
                    if (result) {
                        this.$http.put('promotionals/' + this.id, this.promo).then((response) => {
                            this.$root.$emit('showSuccess', 'Promotional updated.');
                            this.$emit('load-view', {view: 'details', id: response.data.data.id});
                        }, (error) => {
                            this.$root.$emit('showError', 'Could not update promotional.');
                        })
                    }
                });
            },
            callView(data) {
                this.$emit('load-view', data);
            },
            cancel()
            {
                this.$emit('load-view', this.url);
            }
        },
        mounted() {
            if (this.id) this.fetch();
        }
    }
</script>