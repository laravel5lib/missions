<template>
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-6">
                <h5>Promo Codes</h5>
            </div>
            <div class="col-sm-6">
                <div class="input-group input-group-sm">
                    <input type="text" 
                           class="form-control" 
                           v-model="options.params.search" 
                           debounce="250" 
                           placeholder="Search by codes or referral names">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                </div>
            </div>
        </div>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Code</th>
                <th>Referral</th>
                <th>Used</th>
                <th>Issued</th>
                <th>Manage</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="code in codes" track-by="id">
                <td><code>{{ code.code }}</code></td>
                <td v-html="affiliate(code.rewardable)"></td>
                <td>{{ code.use_count}} {{ code.use_count | pluralize 'time'}}</td>
                <td>{{ code.created_at | moment 'lll' }}</td>
                <td>
                    <button class="btn btn-xs btn-primary" 
                            v-show="code.deleted_at" 
                            @click="activate(code.id)">
                        Activate
                    </button>
                    <button class="btn btn-xs btn-default" 
                            v-else 
                            @click="deactivate(code.id)">
                        Deactivate
                    </button>
                </td>
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
        name: 'promocodes',
        props: {
          'promotionalId': {
            type: String,
            required: true
          }
        },
        data() {
            return {
                codes: [],
                options: {
                    params: {
                        promotional: this.promotionalId,
                        withDeactivated: true,
                        include: 'rewardable',
                        per_page: 10,
                        search: null
                    }
                },
                pagination: { current_page: 1},
            }
        },
        watch: {
            'options.params.search': function (val, oldVal) {
                this.pagination.current_page = 1;
                this.fetch();
            }
        },
        events: {},
        methods: {
            fetch() {
                $.extend(this.options.params, {
                    page: this.pagination.current_page
                });

                this.$http.get('promocodes', this.options).then(function (response) {
                    this.codes = response.body.data;
                    this.pagination = response.body.meta.pagination;
                }, function (error) {
                    this.$dispatch('showError', 'Unable to get data from server.');
                });
            },
            affiliate(rewardable) {
                if (! rewardable) return 'none';

                return '<a href="/admin/reservations/'+rewardable.data.id+'" target="_blank">' + rewardable.data.given_names + ' ' + rewardable.data.surname + '</a>';
            },
            activate(codeId) {
                this.$http.put('promocodes/' + codeId + '/restore').then(function (response) {
                    this.$dispatch('showSuccess', 'Promo code activated.');
                    this.fetch();
                }, function (error) {
                    this.$dispatch('showError', 'Could not activate promo code.');
                });
            },
            deactivate(codeId) {
                this.$http.delete('promocodes/' + codeId).then(function (response) {
                    this.$dispatch('showSuccess', 'Promo code deactivated.');
                    this.fetch();
                }, function (error) {
                    this.$dispatch('showError', 'Could not deactivate promo code.');
                });
            }
        },
        ready() {
            this.fetch();
        }
    }
</script>