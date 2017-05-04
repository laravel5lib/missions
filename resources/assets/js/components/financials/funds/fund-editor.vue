<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <spinner v-ref:spinner size="md" text="Updating..."></spinner>
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
        <validator name="validation" :classes="{ invalid: 'has-error' }">
            <label>Balance</label>
            <h4 :class="{'text-success' : fund.balance > 0, 'text-danger' : fund.balance < 0}">
                {{ fund.balance | currency }}
            </h4>
            <div :class="{ 'has-error' : $validation.name.invalid}">
                <label>Fund Name</label>
                <template v-if="editMode">
                    <input class="form-control" v-model="fund.name"
                           initial="off" v-validate:name="{required: true}">
                </template>
                <p v-else>{{ fund.name }}</p>
            </div>
            <div :class="{ 'has-error' : $validation.qbclass.invalid}">
                <label>Account Class</label>
                <template v-if="editMode">
                    <select class="form-control" v-model="fund.class_id"
                           initial="off" v-validate:qbclass="{required: true}">
                       <option v-for="class in accountingClasses" v-bind:value="class.id">
                            {{ class.name }}
                       </option>
                    </select>
                </template>
                <p v-else><code>{{ fund.class }}</code></p>
            </div>
            <div :class="{ 'has-error' : $validation.qbitem.invalid}">
                <label>Account Item</label>
                <template v-if="editMode">
                    <select class="form-control" v-model="fund.item_id"
                           initial="off" v-validate:qbitem="{required: true}">
                       <option v-for="item in accountingItems" v-bind:value="item.id">
                            {{ item.name }}
                       </option>
                    </select>
                </template>
                <p v-else><code>{{ fund.item }}</code></p>
            </div>
            <label>Type</label>
            <p>{{ fund.type | capitalize }}</p>
            <label>Last Updated</label>
            <p>{{ fund.updated_at | moment 'lll' }}</p>
        </validator>
        </div>
    </div>
</template>
<script type="text/javascript">
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
                editMode: false,
                accountingClasses: [],
                accountingItems: []
            }
        },
        methods: {
            fetch() {
                // this.$refs.spinner.show();
                this.$http.get('funds/' + this.id).then(function (response) {
                    this.fund = response.body.data;
                    // this.$refs.spinner.hide();
                });
            },
            save() {
                // validate manually
                let self = this;
                this.$validate(true, function () {
                    if (self.$validation.invalid) {
                        console.log('validation errors');
                    } else {
                        self.$refs.spinner.show();
                        self.$http.put('funds/' + self.id, {
                            'name': self.fund.name,
                            'class': self.fund.class,
                            'item': self.fund.item
                        }).then(function (response) {
                            self.$refs.spinner.hide();
                            self.$root.$emit('showSuccess', 'Fund updated');
                            self.editMode = false;
                            self.fetch();
                        },function (response) {
                            self.$refs.spinner.hide();
                            self.$root.$emit('showError', 'There are errors on the form');
                        });
                    }
                })
            },
            reconcile() {
                this.$http.put('funds/' + this.id + '/reconcile').then(function (response) {
                    this.$root.$emit('showSuccess', 'Fund reconciled');
                    this.fetch();
                });
            }
        },
        events: {
            'reconcileFund': function() {
                this.reconcile();
            }
        },
        ready() {
            this.fetch();
            
            this.$http.get('accounting/classes').then(function (response) {
                this.accountingClasses = response.body.data;
            });

            this.$http.get('accounting/items').then(function (response) {
                this.accountingItems = response.body.data;
            });
        }
    }
</script>