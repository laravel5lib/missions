<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <spinner v-ref:spinner size="sm" text="Loading"></spinner>
                <div class="col-sm-8">
                    <h5>Promotionals</h5>
                </div>
                <div class="col-sm-4 text-right">
                    <a @click="callView({view: 'create-edit'})" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus"></i> New
                    </a>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Credit</th>
                        <th>Expires</th>
                        <th>Codes</th>
                        <th>Created</th>
                        <th>Updated</th>
                        <th><i class="fa fa-cog"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="promo in promos" track-by="id">
                        <td>{{ promo.name | capitalize }}</td>
                        <td>{{ promo.reward | currency }}</td>
                        <td>{{ promo.expires | moment 'll' }}</td>
                        <td>{{ promo.promocodes_count }}</td>
                        <td>{{ promo.created_at | moment 'll' }}</td>
                        <td>{{ promo.updated_at | moment 'll' }}</td>
                        <th><a @click="callView({view: 'details', id: promo.id})"><i class="fa fa-cog"></i></a></th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script type="text/javascript">
    export default {
        name: 'list',
        props: {
          'promoterType': {
            type: String,
            required: true
          },
          'promoterId': {
            type: String,
            required: true
          }
        },
        data() {
            return {
                promos: [],
                options: {
                    params: {
                        promoterType: this.promoterType, 
                        promoterId: this.promoterId
                    }
                }
            }
        },
        methods: {
            fetch() {
                this.$http.get('promotionals', this.options).then(function (response) {
                    this.promos = response.body.data;
                    this.pagination = response.body.meta.pagination;
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