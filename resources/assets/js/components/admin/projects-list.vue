<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-6">
                    <h5>Projects</h5>
                </div>
                <div class="col-xs-6 text-right">
                    <a href="#" class="btn btn-primary btn-xs">Start Project</a>
                </div>
            </div>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Sponsor</th>
                    <th>Type</th>
                    <th>Country</th>
                    <th>Manage</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="project in projects">
                    <td>{{ project.name }}</td>
                    <td>
                        {{ project.sponsor.data.name }}
                        <br /><small>{{ project.sponsor_type == 'users' ? 'Individual' : 'Group' }}</small>
                    </td>
                    <td>{{ project.type.data.name }}</td>
                    <td>{{ project.type.data.country.name }}</td>
                    <td><a href="/admin/projects/{{ project.id }}"><i class="fa fa-cog"></i></a></td>
                </tr>
            </tbody>
        </table>
        <div class="list-group-item text-center" v-if="pagination.per_page < pagination.total">
            <nav>
                <ul class="pager pager-sm">
                    <li :class="{ 'disabled': pagination.current_page == 1 }" class="previous">
                        <a aria-label="Previous" @click="page=pagination.current_page-1">
                            <span aria-hidden="true">&laquo; Previous</span>
                        </a>
                    </li>
                    <li>page {{ pagination.current_page }} of {{ pagination.total_pages }}</li>
                    <li :class="{ 'disabled': pagination.current_page == pagination.total_pages }" class="next">
                        <a aria-label="Next" @click="page=pagination.current_page+1">
                            <span aria-hidden="true">Next &raquo; </span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>
<script>
    import vSelect from 'vue-select';
    export default {
        name: 'projects-list',
        data() {
            return{
                projects: [],
                pagination: {},
                page: 1
            }
        },
        props: {
            'causeId': {
                type: String,
                required: true
            }
        },
        components:{
            'v-select': vSelect,
        },
        watch : {
            'page': function (val, oldVal) {
                this.fetch();
            }
        },
        methods: {
            fetch() {
                this.$http.get('causes/' + this.causeId + '/projects?include=sponsor,type&page=' + this.page)
                .then(function (response) {
                    this.projects = response.data.data;
                    this.pagination = response.data.meta.pagination;
                });
            }
        },
        ready () {
            this.fetch();
        }
    }
</script>