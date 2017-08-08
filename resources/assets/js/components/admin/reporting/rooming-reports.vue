<template>
    <div class="btn-group btn-group-sm">
        <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-line-chart"></i> Create Report <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a @click="create('plans')"><i class="fa fa-file-excel-o"></i> Rooms by Plan</a></li>
        </ul>
    </div>
</template>
<script>
export default {
    name: 'rooming-reports',
    props: ['filters', 'search', 'campaign'],
    methods: {
        create(report) {
            let params = { 'author_id': this.$root.user.id, 'campaign': this.campaign };
            this.filters.group ? this.filters.group = this.filters.group.id : null;
            $.extend(params, this.filters);
            $.extend(params, {search: this.search});
            console.log(report);
            this.$http.post('reports/' + report + '/rooms', params).then(function (response) {
                this.$root.$emit('showSuccess', response.body.message);
                this.report = '';
            }, function (error) {
                this.$root.$emit('showError', 'Unable to create the report.');
            })
        }
    }
}
</script>