<template>
<div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <spinner v-ref:spinner size="sm" text="Loading"></spinner>
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
                    <button class="btn btn-primary-darker btn-sm">
                        <i class="fa fa-trash-o"></i>
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
                    {{ promo.created_at | moment 'lll' }}<br>
                    <label>Created</label>
                </div>
                <div class="col-sm-4">
                    {{ promo.updated_at | moment 'lll' }}<br>
                    <label>Updated</label>
                </div>
                <div class="col-sm-4">
                    {{ deactivated }}<br>
                    <label>Deactivated</label>
                </div>
            </div>
        </div>
    </div>

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
                }
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
                    this.$dispatch('showError', 'Unable to get data from server.');
                });
            },
            callView(data) {
                this.$dispatch('load-view', data);
            }
        },
        ready() {
            this.fetch();
        }
    }
</script>