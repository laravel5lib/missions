<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-4">
                    <h5 class="panel-header">Details</h5>
                </div>
                <div class="col-xs-8 text-right" v-if=" ! readOnly">
                    <button class="btn btn-xs btn-default-hollow" @click="reconcile" v-if="!editMode">
                        <i class="fa fa-calculator"></i> Reconcile
                    </button>
                    <button class="btn btn-xs btn-default"
                            @click="editMode = !editMode"
                            v-if="!editMode">
                        Edit
                    </button>
                    <button class="btn btn-xs btn-default"
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
            <label>Balance</label>
            <h4 :class="{'text-success' : fund.balance > 0, 'text-danger' : fund.balance < 0}">
                {{ fund.balance | currency }}
            </h4>
            <label>Fund Name</label>
            <input class="form-control" v-model="fund.name" v-if="editMode">
            <p v-else>{{ fund.name }}</p>
            <label>QuickBooks Class</label>
            <input class="form-control" v-model="fund.class" v-if="editMode">
            <p v-else>{{ fund.class }}</p>
            <label>QuickBooks Item</label>
            <input class="form-control" v-model="fund.item" v-if="editMode">
            <p v-else>{{ fund.item }}</p>
            <label>Type</label>
            <p>{{ fund.type | capitalize }}</p>
            <label>Last Updated</label>
            <p>{{ fund.updated_at | moment 'lll' }}</p>
        </div>

        <alert :show.sync="showSuccess" placement="top-right" :duration="3000" type="success" width="400px" dismissable>
            <span class="icon-ok-circled alert-icon-float-left"></span>
            <strong>Awesome!</strong>
            <p>{{ message }}</p>
        </alert>
    </div>
</template>
<script>
    export default{
        name: 'fund-editor',
        props: {
            'id': {
                type: String,
                required: true
            },
            'readOnly': {
                type: Boolean,
                default: false
            }
        },
        data(){
            return{
                fund: {},
                showSuccess: false,
                message: '',
                editMode: false
            }
        },
        methods: {
            fetch() {
                this.$http.get('funds/' + this.id).then(function (response) {
                    this.fund = response.data.data;
                });
            },
            save() {
                this.$http.put('funds/' + this.id, this.fund).then(function (response) {
                    this.message = 'Fund has been updated!';
                    this.showSuccess = true;
                    this.editMode = false;
                    this.fetch();
                });
            },
            reconcile() {
                this.$http.put('funds/' + this.id + '/reconcile').then(function (response) {
                    this.message = 'Fund has been reconciled!';
                    this.showSuccess = true;
                    this.fetch();
                });
            }
        },
        ready() {
            this.fetch();
        }
    }
</script>