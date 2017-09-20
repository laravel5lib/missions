<template>
    <div>
        <div class="row">
            <div class="col-sm-12">
                <form class="form-inline text-right" novalidate>
                    <template v-if="app.user.can.create_reports">
                        <export-utility url="causes/export"
                             :options="exportOptions"
                             :filters="exportFilters">
                        </export-utility>
                    </template>
                </form>
            </div>
        </div>
        <hr>
        <div class="row" style="display:flex; flex-wrap: wrap; flex-direction: row;">
            <div v-for="(cause, index) in causes" class="col-sm-6 col-md-4" style="display:flex">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5 class="text-center">{{ cause.name }}</h5>
                    </div>
                    <div class="panel-body text-center">
                        <!--<p class="badge">{{ cause.status|capitalize }}</p><br>-->
                        <p class="small">{{ cause.short_desc }}</p>
                        <label>Countries</label>
                        <p class="small"><span v-for="country in cause.countries">
								{{ country.name }}<span v-show="index + 1 != cause.countries.length">, </span>
						</span></p>
                        <label>Projects Funded</label>
                        <p>{{ cause.projects_funded }}</p>
                        <!--<h3 class="text-success">{{ currency(trip.starting_cost) }}</h3>-->
                        <a :href="'/admin/causes/' +  cause.id  + '/current-projects'" class="btn btn-primary-hollow btn-sm"><i class="fa fa-cog"></i> Manage</a>
                    </div>
                </div>
            </div>
        </div><!-- end row -->

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