<template>
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
                </div>
            </div>
        </div>
        <div class="panel-body">
            {{ promo.reward | currency }}
            <label>Credit Amount</label>
        </div>
    </div>
</template>
<script type="text/javascript">
    export default {
        name: 'details',
        props: {
          'id': {
            type: String,
            required: true
          }
        },
        data() {
            return {
                promo: {
                    id: null,
                    name: null,
                    reward: null,
                    expires_at: null
                }
            }
        },
        events: {},
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