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
        <div class="panel-body text-muted text-center" v-if="!promos.length">
            <p class="lead">
                No promotionals found.<br />
                <small v-if="hasFilteredResults">Try modifying your search terms or filters.</small>
                <small v-else>Create a new one to get started.</small>
            </p>
        </div>
        <table class="table table-striped" v-else>
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
        <div class="panel-footer">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <pagination :pagination.sync="pagination" :callback="fetch"></pagination>
                </div>
            </div>
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
                        promoterId: this.promoterId,
                        per_page: 10,
                        search: '',
                        filters: []
                    }
                },
                pagination: { current_page: 1},
            }
        },
        computed: {
            hasFilteredResults() {
                return this.options.params.search || this.options.params.filters.length;
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