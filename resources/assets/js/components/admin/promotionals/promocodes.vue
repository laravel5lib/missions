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
                    <span class="input-group-addon">
                        <i class="fa fa-circle-o-notch fa-spin" v-show="loading"></i>
                        <i class="fa fa-search" v-else></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <table class="table table-striped" v-show="codes.length">
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
            <tr v-for="code in codes" track-by="id" transition="stagger" stagger="50">
                <td><code>{{ code.code }}</code></td>
                <td v-html="affiliate(code.rewardable)"></td>
                <td>{{ code.use_count}} {{ pluralize(code.use_count , 'time')}}</td>
                <td>{{ code.created_at | moment('lll') }}</td>
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
    <div class="panel-footer text-center" v-else>
        No promo codes found.
    </div>
    <div class="panel-footer">
        <div class="row">
            <div class="col-xs-12 text-center">
                <pagination :pagination="pagination" :callback="fetch"></pagination>
            </div>
        </div>
    </div>
</div>
</template>
<style scoped>
    .stagger-transition {
        transition: all .2s ease;
        overflow: hidden;
        margin: 0;
        height: 20px;
    }
    .stagger-enter, .stagger-leave {
        opacity: 0;
        height: 0;
    }
</style>
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
                loading: false
            }
        },
        watch: {
            'options.params.search': (val, oldVal) =>  {
                this.pagination.current_page = 1;
                this.fetch();
            }
        },
        events: {
            'promotionalStatusChanged': function (msg) {
                this.fetch();
            }
        },
        methods: {
            fetch() {
                this.loading = true;

                $.extend(this.options.params, {
                    page: this.pagination.current_page
                });

                this.$http.get('promocodes', this.options).then((response) => {
                    this.codes = response.data.data;
                    this.pagination = response.data.meta.pagination;
                    this.loading = false;
                }, (error) =>  {
                    this.$root.$emit('showError', 'Unable to get data from server.');
                    this.loading = false;
                });
            },
            affiliate(rewardable) {
                if (! rewardable) return 'none';

                return '<i class="fa fa-external-link text-muted"></i> <a href="/admin/reservations/'+rewardable.data.id+'" target="_blank">' + rewardable.data.given_names + ' ' + rewardable.data.surname + '</a>';
            },
            activate(codeId) {
                this.$http.put('promocodes/' + codeId + '/restore').then((response) => {
                    this.$root.$emit('showSuccess', 'Promo code activated.');
                    this.fetch();
                }, (error) =>  {
                    this.$root.$emit('showError', 'Could not activate promo code.');
                });
            },
            deactivate(codeId) {
                this.$http.delete('promocodes/' + codeId).then((response) => {
                    this.$root.$emit('showSuccess', 'Promo code deactivated.');
                    this.fetch();
                }, (error) =>  {
                    this.$root.$emit('showError', 'Could not deactivate promo code.');
                });
            }
        },
        mounted() {
            this.fetch();
        }
    }
</script>