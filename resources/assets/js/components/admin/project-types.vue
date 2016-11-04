<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-6">
                    <h5>Project Types</h5>
                </div>
                <div class="col-xs-6 text-right">
                    <a href="/admin/causes/{{ causeId }}/types/create" class="btn btn-default-hollow btn-xs">
                        <i class="fa fa-plus"></i> New
                    </a>
                </div>
            </div>
        </div>
        <div class="list-group">
            <div class="list-group-item">
                <div class="row">
                    <div class="col-xs-6">
                        <em>Type</em>
                    </div>
                    <div class="col-xs-2 text-center">
                        <em>Country</em>
                    </div>
                    <div class="col-xs-2 text-center">
                        <em>Projects</em>
                    </div>
                    <div class="col-xs-2 text-center">
                        <em>Manage</em>
                    </div>
                </div>
            </div>
            <div class="list-group-item" v-for="type in types">
                <div class="row">
                    <div class="col-xs-6">
                        <strong>{{ type.name }}</strong>
                    </div>
                    <div class="col-xs-2 text-center">
                        {{ type.country.code | uppercase }}
                    </div>
                    <div class="col-xs-2 text-center">
                        <span class="badge badge-info">{{ type.projects_count }}</span>
                    </div>
                    <div class="col-xs-2 text-center">
                        <a href="/admin/causes/{{ causeId }}/types/{{ type.id }}">
                            <i class="fa fa-cog"></i>
                        </a>
                    </div>
                </div>
            </div>
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
    </div>
</template>
<script>
    export default{
        name: 'project-types',
        data() {
            return{
                types: [],
                pagination: {},
                page: 1
            }
        },
        props: {
            'causeId': {
                type: String,
                required: true
            },
            'perPage': {
                type: Number,
                default: 3
            }
        },
        watch : {
            'page': function (val, oldVal) {
                this.fetch();
            }
        },
        methods: {
            fetch() {
                this.$http.get('causes/' + this.causeId + '/types?per_page=' + this.perPage + '&page=' + this.page)
                .then(function (response) {
                    this.types = response.data.data;
                    this.pagination = response.data.meta.pagination;
                });
            }
        },
        ready() {
            this.fetch();
        }
    }
</script>