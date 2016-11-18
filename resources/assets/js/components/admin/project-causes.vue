<template>
    <div>

        <div class="row">
            <div v-for="cause in causes" class="col-sm-6 col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5 class="text-center">{{ cause.name }}</h5>
                    </div>
                    <div class="panel-body text-center">
                        <!--<p class="badge">{{ cause.status | capitalize }}</p><br>-->
                        <p class="small">{{ cause.short_desc }}</p>
                        <label>Countries</label>
                        <p class="small"><span v-for="country in cause.countries">
								{{ country.name }}<span v-show="$index + 1 != cause.countries.length">, </span>
						</span></p>
                        <label>Projects Funded</label>
                        <p>{{ cause.projects_funded }}</p>
                        <!--<h3 class="text-success">{{ trip.starting_cost | currency }}</h3>-->
                        <a href="/admin/causes/{{ cause.id }}/current-projects" class="btn btn-primary-hollow btn-sm"><i class="fa fa-cog"></i> Manage</a>
                    </div>
                </div>
            </div>
        </div><!-- end row -->

    </div>
</template>
<script>
    export default{
        name: 'project-causes',
        data() {
            return{
                causes: {},
                pagination: {}
            }
        },
        methods: {
            fetch() {
                this.$http.get('causes').then(function (response) {
                    this.causes = response.data.data;
                    this.pagination = response.data.meta.pagination;
                });
            }
        },
        ready() {
            this.fetch();
        }
    }
</script>