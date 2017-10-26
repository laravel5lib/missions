<template>
    <div>
        <div class="row">         
            <div class="col-xs-12 panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-6">
                            <h3>Project Causes</h3>
                        </div>
                        <div class="col-xs-6 text-right">
                            <hr class="divider inv sm">
                            <button data-toggle="modal" data-target="#causeEditor" class="btn btn-primary">
                                <i class="fa fa-plus icon-left"></i> New 
                            </button>
                        </div>
                    </div>
                </div>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Cause</th>
                            <th>Countries</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(cause, index) in causes">
                            <td>{{ cause.name }}</td>
                            <td>
                                <span v-for="country in cause.countries">
                                    {{ country.name }}<span v-show="index + 1 != cause.countries.length">, </span>
                                </span>
                            </td>
                            <td>
                                <a :href="'/admin/causes/' +  cause.id  + '/current-projects'"><i class="fa fa-cog"></i> Manage</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
    import exportUtility from '../export-utility.vue';
    export default{
        name: 'project-causes',
        components: {exportUtility},
        data() {
            return{
                app: MissionsMe,
                causes: {},
                pagination: {},
                exportOptions: {
                    id: 'ID',
                    name: 'Name',
                    short_desc: 'Short Description',
                    countries: 'Countries',
                    country_codes: 'Country Codes',
                    created_at: 'Created On',
                    updated_at: 'Last Updated',
                },
                exportFilters: {},
            }
        },
        methods: {
            fetch() {
                this.$http.get('causes').then((response) => {
                    this.causes = response.data.data;
                    this.pagination = response.data.meta.pagination;
                });
            }
        },
        mounted() {
            this.fetch();
        }
    }
</script>