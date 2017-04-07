<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <spinner v-ref:spinner size="sm" text="Loading"></spinner>
                <div class="col-sm-8">
                    <h5>Create New Promotional</h5>
                </div>
                <div class="col-sm-4 text-right">
                    <button @click="callView({view: 'list'})" class="btn btn-primary btn-sm">
                        <i class="fa fa-chevron-left"></i> Back
                    </button>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <form>
                <div class="form-group col-sm-6">
                    <label>Promotional Name</label>
                    <input type="text" v-model="promo.name" class="form-control">
                </div>
                <div class="form-group col-sm-6">
                    <label>Referral Affiliates</label>
                    <select v-model="promo.affiliates" class="form-control">
                        <option :value="null">-- no affiliates --</option>
                        <option value="reservations">Reservations</option>
                    </select>
                </div>
                <div class="form-group col-sm-6">
                    <label>Credit Amount</label>
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="number" v-model="promo.reward" class="form-control">
                        <span class="input-group-addon">.00</span>
                    </div>
                </div>
                <div class="form-group col-sm-6">
                    <label>Expires</label>
                    <date-picker :model.sync="promo.expires_at|moment 'YYYY-MM-DD' false true"></date-picker>
                </div>
            </form>
        </div>
        <div class="panel-body text-right">
            <button class="btn btn-default" @click="cancel()">Cancel</button>
            <button class="btn btn-primary" @click="create()">Create</button>
        </div>
    </div>
</template>
<script type="text/javascript">
    export default {
        name: 'create-edit',
        props: {
          'type': {
            type: String,
            required: false
          },
          'id': {
            type: String,
            required: false
          }
        },
        data() {
            return {
                promo: {
                    name: null,
                    reward: 0,
                    expires_at: null,
                    affiliates: null
                }
            }
        },
        events: {},
        methods: {
            resetForm() {
                this.promo = {
                    name: null,
                    reward: 0,
                    expires_at: null,
                    affiliates: null
                }
            },
            create() {

            },
            update() {

            },
            callView(data) {
                this.resetForm();
                this.$dispatch('load-view', data);
            },
            cancel()
            {
                this.resetForm();
                this.$dispatch('load-view', {view:'list'});
            }
        }
    }
</script>