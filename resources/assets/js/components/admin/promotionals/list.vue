<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <spinner ref="spinner" global size="sm" text="Loading"></spinner>
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
        <template v-if="!promos.length">
            <div class="panel-body text-muted text-center">
                <p class="lead">
                    No promotionals found.<br />
                    <small v-if="hasFilteredResults">Try modifying your search terms or filters.</small>
                    <small v-else>Create a new one to get started.</small>
                </p>
            </div>
        </template>
        <template v-else>
            <table class="table table-striped" >
                <thead>
                <tr>
                    <th>Status</th>
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
                <tr v-for="promo in promos" :key="promo.id">
                    <td><span class="label label-default">{{ status(promo) }}</span></td>
                    <td>{{ promo.name|capitalize }}</td>
                    <td>{{ currency(promo.reward) }}</td>
                    <td>{{ promo.expires | moment('ll') }}</td>
                    <td>{{ promo.promocodes_count }}</td>
                    <td>{{ promo.created_at | moment('ll') }}</td>
                    <td>{{ promo.updated_at | moment('ll') }}</td>
                    <th><a @click="callView({view: 'details', id: promo.id})"><i class="fa fa-cog"></i></a></th>
                </tr>
                </tbody>
            </table>
        </template>

        <div class="panel-footer">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <pagination :pagination="pagination" pagination-key="pagination" :callback="fetch"></pagination>
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
                        withInactive: true,
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
            status(promo) {
                if (promo.deleted_at || promo.expired_at < moment()) return 'Stopped';

                return 'Active';
            },
            fetch() {
                this.$http.get('promotionals', this.options).then((response) => {
                    this.promos = response.data.data;
                    this.pagination = response.data.meta.pagination;
                }, (error) =>  {
                    this.$root.$emit('showError', 'Unable to get data from server.');
                });
            },
            callView(data) {
                this.$emit('load-view', data);
            }
        },
        mounted() {
            this.fetch();
        }
    }
</script>