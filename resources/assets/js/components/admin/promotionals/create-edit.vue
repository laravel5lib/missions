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
            <validator name="ModifyPromotional" @touched="onTouched">

            <form id="ModifyPromotional" novalidate>
            <div class="row">
                <div class="form-group col-sm-6" 
                     v-error-handler="{ value: promo.name, client: 'name', server: 'name' }">

                    <label>Promotional Name</label>
                    <input type="text" 
                           v-model="promo.name" 
                           class="form-control" 
                           v-validate:name="{ required: true, minlength:3, maxlength:100 }"
                           required>
                    <span v-if="attemptSubmit" class="help-block">
                        <span v-if="$ModifyPromotional.name.required || $ModifyPromotional.name.minlength">
                            Please provide a name at least 3 characters long.
                        </span>
                        <span v-if="$ModifyPromotional.name.maxlength">
                            Please provide a name less than 100 characters long.
                        </span>
                    </span>
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
                     v-error-handler="{ value: promo.reward, client: 'reward', server: 'reward' }">
                    <label>Credit Amount</label>
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="number" 
                               v-model="promo.reward" 
                               class="form-control" 
                               v-validate:reward="{ required: true, min:1 }" 
                               required>
                        <span class="input-group-addon">.00</span>
                    </div>
                    <span class="help-block" v-if="id">This will not change credits already applied.</span>
                    <span v-if="attemptSubmit" class="help-block">
                        <span v-if="$ModifyPromotional.reward.required || $ModifyPromotional.reward.min">
                            Please provide a credit amount of $1.00 or more.
                        </span>
                    </span>
                </div>

                <div class="form-group col-sm-6">
                    <label>Expires</label>
                    <date-picker :model.sync="promo.expires_at|moment('YYYY-MM-DD', false, true)"></date-picker>
                </div>
            </div>
            </form>
        </div>
        <div class="panel-body text-right">
            <button class="btn btn-default" @click="cancel()">Cancel</button>
            <button class="btn btn-primary" @click="update()" v-if="id">Update</button>
            <button class="btn btn-primary" @click="create()" v-else>Create</button>
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
                validatorHandle: 'ModifyPromotional',
                promo: {
                    name: null,
                    reward: 0,
                    expires_at: null,
                    affiliates: null
                },
                hasChanged: false
            }
        },
        computed: {
            url() {
                return this.id ? {view: 'details', id: this.id} : {view: 'list'};
            }
        },
        events: {},
        methods: {
            onTouched(){
                this.hasChanged = true;
            },
            fetch() {
                this.$http.get('promotionals/' + this.id).then(function (response) {
                    this.promo = response.body.data;
                }, function (error) {
                    this.$root.$emit('showError', 'Unable to get data from server.');
                })
            },
            create() {
                this.resetErrors();
                if (this.$ModifyPromotional.valid) {
                    $.extend(this.promo, {promoteable_id: this.promoterId, promoteable_type: this.promoterType});
                    this.$http.post('promotionals', this.promo).then(function (response) {
                        this.$root.$emit('showSuccess', 'Promotional created.');
                        this.$dispatch('load-view', {view: 'details', id: response.body.data.id});
                    }, function (error) {
                        this.$root.$emit('showError', 'Could not create promotional.');
                    })
                }
            },
            update() {
                // Touch fields for proper validation
                if ( _.isFunction(this.$validate) )
                    this.$validate(true);

                this.resetErrors();
                if (this.$ModifyPromotional.valid) {
                    this.$http.put('promotionals/' + this.id, this.promo).then(function (response) {
                        this.$root.$emit('showSuccess', 'Promotional updated.');
                        this.$dispatch('load-view', {view: 'details', id: response.body.data.id});
                    }, function (error) {
                        this.$root.$emit('showError', 'Could not update promotional.');
                    })
                }
            },
            callView(data) {
                this.$dispatch('load-view', data);
            },
            cancel()
            {
                this.$dispatch('load-view', this.url);
            }
        },
        mounted() {
            if (this.id) this.fetch();
        }
    }
</script>