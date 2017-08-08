<template>
    <div class="btn-group btn-group-sm">
        <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-line-chart"></i> Create Report <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a @click="create('passengers')"><i class="fa fa-file-excel-o"></i> Passengers by Transport</a></li>
        </ul>
    </div>
</template>
<script>
export default {
    name: 'transportation-reports',
    props: ['filters', 'search'],
    methods: {
        create(report) {
            let params = { 'author_id': this.$root.user.id };
            $.extend(params, this.filters);
            $.extend(params, {search: this.search});
            console.log(report);
            this.$http.post('reports/transports/' + report, params).then(function (response) {
                this.$root.$emit('showSuccess', response.body.message);
                this.report = '';
            }, function (error) {
                this.$root.$emit('showError', 'Unable to create the report.');
            })
        }
    }
}
</script>