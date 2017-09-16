<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <spinner ref="spinner" size="md" text="Updating..."></spinner>
            <div class="row">
                <div class="col-xs-4">
                    <h5 class="panel-header">Details</h5>
                </div>
                <div class="col-xs-8 text-right" v-if=" ! readOnly && app.user.can.update_funds">
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
                {{ currency(fund.balance) }}
            </h4>
            <div :class="{ 'has-error' : errors.has('name')}">
                <label>Fund Name</label>
                <template v-if="editMode">
                    <input class="form-control" v-model="fund.name"
                            name="name" v-validate="'required'">
                </template>
                <p v-else>{{ fund.name }}</p>
            </div>
            <div :class="{ 'has-error' : errors.has('qbclass')}">
                <label>Account Class</label>
                <template v-if="editMode">
                    <select class="form-control" v-model="fund.class_id"
                            name="qbclass" v-validate="'required'">
                       <option v-for="c in accountingClasses" :value="c.id">
                            {{ c.name }}
                       </option>
                    </select>
                </template>
                <p v-else><code>{{ fund.class }}</code></p>
            </div>
            <div :class="{ 'has-error' : errors.has('qbitem')}">
                <label>Account Item</label>
                <template v-if="editMode">
                    <select class="form-control" v-model="fund.item_id"
                            name="qbitem" v-validate="'required'">
                       <option v-for="item in accountingItems" :value="item.id">
                            {{ item.name }}
                       </option>
                    </select>
                </template>
                <p v-else><code>{{ fund.item }}</code></p>
            </div>
            <label>Type</label>
            <p>{{ fund.type|capitalize }}</p>
            <label>Last Updated</label>
            <p>{{ fund.updated_at | moment('lll') }}</p>

        </div>
        <div class="panel-footer text-right" v-if="app.user.can.delete_funds">
            <a class="btn btn-default btn-sm" data-toggle="modal" data-target="#deleteConfirmationModal">
                <i class="fa fa-archive"></i> Archive
            </a>
        </div>

        <admin-delete-modal
            :id="id"
            resource="fund"
            label="Archive this Fund?"
            action="Archive">
        </admin-delete-modal>
    </div>
</template>
<script type="text/javascript">
    import adminDeleteModal from '../../admin-delete-modal.vue';
    export default{
        name: 'fund-editor',
        components: {adminDeleteModal},
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
                app: MissionsMe,
                fund: {},
                editMode: false,
                accountingClasses: [],
                accountingItems: []
            }
        },
        methods: {
            fetch() {
                // this.$refs.spinner.show();
                this.$http.get('funds/' + this.id).then((response) => {
                    this.fund = response.data.data;
                    // this.$refs.spinner.hide();
                });
            },
            save() {
                // validate manually
                let self = this;
                this.$validator.validateAll().then(result => {
                    if (!result) {
                        console.log('validation errors');
                    } else {
                        self.$refs.spinner.show();
                        self.$http.put('funds/' + self.id, {
                            'name': self.fund.name,
                            'class_id': self.fund.class_id,
                            'item_id': self.fund.item_id
                        }).then((response) => {
                            self.$refs.spinner.hide();
                            self.$root.$emit('showSuccess', 'Fund updated');
                            self.editMode = false;
                            self.fetch();
                        },(response) =>  {
                            self.$refs.spinner.hide();
                            self.$root.$emit('showError', 'There are errors on the form');
                        });
                    }
                })
            },
            reconcile() {
                this.$http.put('funds/' + this.id + '/reconcile').then((response) => {
                    this.$root.$emit('showSuccess', 'Fund reconciled');
                    this.fetch();
                });
            },
            archive() {
                this.$http.delete('funds/' + this.id).then((response) => {
                    this.$root.$emit('showSuccess', 'Fund archived');
                    this.fetch();
                });
            }
        },
        events: {
            'reconcileFund': function() {
                this.reconcile();
            }
        },
        mounted() {
            this.fetch();

            this.$http.get('accounting/classes').then((response) => {
                this.accountingClasses = response.data.data;
            });

            this.$http.get('accounting/items').then((response) => {
                this.accountingItems = response.data.data;
            });
        }
    }
</script>