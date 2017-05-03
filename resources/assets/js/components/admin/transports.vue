<template>
    <div>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    
                    <div class="panel-heading">
                        <h5>Transports</h5>
                    </div>
                    <div class="panel-body">
                        <div class="btn-group btn-group-sm" role="group" aria-label="...">
                          <button type="button" class="btn btn-default-hollow" @click="changeList('international')">International</button>
                          <button type="button" class="btn btn-default" @click="changeList('domestic')">Domestic</button>
                        </div>
                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Name</th>
                                <th>Number</th>
                                <th><i class="fa fa-cog"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="transport in transports">
                                <td>{{ transport.type | capitalize }}</td>
                                <td>{{ transport.name }}</td>
                                <td>{{ transport.vessel_no }}</td>
                                <td><i class="fa fa-cog"></i></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

    </div>
</template>
<script type="text/javascript">
    export default {
        name: 'transports',
        props: {
          'campaignId': {
            type: String,
            required: true
          }
        },
        data() {
            return {
                transports: [],
                options: {
                    params: {
                        isDomestic: 'yes',
                        campaign: this.campaignId,
                        per_page: 10,
                        search: '',
                        filters: []
                    }
                },
                pagination: { current_page: 1},
            }
        },
        methods: {
            changeList(tab) {
                if (tab == 'domestic') {
                    this.options.params.isDomestic = 'yes';
                    this.fetch();
                } else {
                    this.options.params.isDomestic = 'no';
                    this.fetch();
                }
            },
            fetch() {
                this.$http.get('transports', this.options).then(function (response) {
                    this.transports = response.body.data;
                    this.pagination = response.body.meta.pagination;
                }, function (error) {
                    this.$dispatch('showError', 'Unable to get data from server.');
                });
            }
        },
        ready() {
            this.fetch();
        }
    }
</script>