<template>
<div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <spinner ref="spinner" size="sm" text="Loading"></spinner>
                <div class="col-sm-8">
                    <h5>{{ promo.name }}</h5>
                </div>
                <div class="col-sm-4 text-right">
                    <button @click="callView({view:'list'})" class="btn btn-default btn-sm">
                        <i class="fa fa-chevron-left"></i> Back
                    </button>
                    <button @click="callView({view:'create-edit', id: promo.id})" class="btn btn-primary btn-sm">
                        Edit
                    </button>
                    <button class="btn btn-primary-darker btn-sm" @click="showModal()">
                        <span v-if="promo.deleted_at"><i class="fa fa-play"></i> Start</span>
                        <span v-else><i class="fa fa-ban"></i> Stop</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-4">
                    {{ promo.reward | currency }}<br>
                    <label>Credit Amount</label>
                </div>
                <div class="col-sm-4">
                    {{ expires }}<br>
                    <label>Expires</label>
                </div>
                <div class="col-sm-4">
                    {{ promo.promocodes_count }}<br>
                    <label>Codes</label>
                </div>
            </div>
            <div class="row">
                <hr class="divider">
                <div class="col-sm-4">
                    {{ promo.created_at | moment('lll') }}<br>
                    <label>Created</label>
                </div>
                <div class="col-sm-4">
                    {{ promo.updated_at | moment('lll') }}<br>
                    <label>Updated</label>
                </div>
                <div class="col-sm-4">
                    {{ deactivated }}<br>
                    <label>Deactivated</label>
                </div>
            </div>
        </div>
        <div class="panel-footer" v-if="promo.deleted_at">
            <span class="help-block text-right">
                <a @click="showDeleteModal = true"><i class="fa fa-trash"></i> Delete Forever</a>
            </span>
        </div>
    </div>

    <modal title="Stop Promotional" small :show.sync="showStopModal">
        <div slot="modal-body" class="modal-body">Are you sure you want to stop this promotional? Doing so will deactivate all it's promo codes. You can always undo this action or reactivate individual promo codes.</div>
        <div slot="modal-footer" class="modal-footer">
            <button type="button" class="btn btn-default" @click="showStopModal = false">Cancel</button>
            <button type="button" class="btn btn-primary" @click="deactivate()">Stop</button>
          </div>
    </modal>

    <modal title="Start Promotional" small :show.sync="showStartModal">
        <div slot="modal-body" class="modal-body">Are you sure you want to start this promotional? Doing so will activate all it's promo codes. You can always undo this action or deactivate individual promo codes.</div>
        <div slot="modal-footer" class="modal-footer">
            <button type="button" class="btn btn-default" @click="showStartModal = false">Cancel</button>
            <button type="button" class="btn btn-primary" @click="activate()">Start</button>
          </div>
    </modal>

    <modal title="Delete Forever" small :show.sync="showDeleteModal">
        <div slot="modal-body" class="modal-body">Are you sure you want to delete this promotional and all it's promo codes? This action cannot be undone.</div>
        <div slot="modal-footer" class="modal-footer">
            <button type="button" class="btn btn-default" @click="showDeleteModal = false">Keep</button>
            <button type="button" class="btn btn-primary" @click="deleteForever()">Delete</button>
          </div>
    </modal>

    <promocodes :promotional-id="id"></promocodes>

</div>
</template>
<script type="text/javascript">
    import promocodes from './promocodes.vue';
    export default {
        name: 'details',
        props: {
          'id': {
            type: String,
            required: true
          }
        },
        components: [promocodes],
        data() {
            return {
                promo: {
                    id: null,
                    name: null,
                    reward: 0,
                    expires_at: null,
                    promocodes_count: 0,
                    created_at: null,
                    updated_at: null
                },
                showStartModal: false,
                showStopModal: false,
                showDeleteModal: false
            }
        },
        events: {},
        computed: {
            expires() {
                if (this.promo.expires_at) return moment(this.promo.expires_at).format('lll');

                return 'Does not expire';
            },
            deactivated() {
                if(this.promo.deleted_at) return moment(this.promo.deleted_at).format('lll');

                return 'Still active';
            }
        },
        methods: {
            fetch() {
                this.$http.get('promotionals/' + this.id).then(function (response) {
                    this.promo = response.body.data;
                }, function (error) {
                    this.$root.$emit('showError', 'Unable to get data from server.');
                });
            },
            callView(data) {
                this.$dispatch('load-view', data);
            },
            showModal() {
                if (this.promo.deleted_at) return this.showStartModal = true;

                return this.showStopModal = true;
            },
            deactivate()
            {
                this.$http.delete('promotionals/' + this.id).then(function (response) {
                    this.$root.$emit('showSuccess', 'Deactivated successfully.');
                    this.showStopModal = false;
                    this.fetch();
                    this.$broadcast('promotionalStatusChanged');
                }, function (error) {
                    this.$root.$emit('showError', 'Unable to deactivate on server.');
                });
            },
            deleteForever()
            {
                this.$http.delete('promotionals/' + this.id +'?force=true').then(function (response) {
                    this.$root.$emit('showSuccess', 'Deleted promotional.');
                    this.showDeleteModal = false;
                    this.callView({view: 'list'});
                }, function (error) {
                    this.$root.$emit('showError', 'Unable to delete on server.');
                });
            },
            activate()
            {
                this.$http.put('promotionals/' + this.id + '/restore').then(function (response) {
                    this.$root.$emit('showSuccess', 'Activated successfully.');
                    this.showStartModal = false;
                    this.fetch();
                    this.$broadcast('promotionalStatusChanged');
                }, function (error) {
                    this.$root.$emit('showError', 'Unable to activate on server.');
                });
            },
            callView(data) {
                this.$dispatch('load-view', data);
            }
        },
        mounted() {
            this.fetch();
        }
    }
</script>