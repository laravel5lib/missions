<template>
    <div class="btn-group btn-group-sm">
      <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-line-chart"></i> Create Report <span class="caret"></span>
      </button>
      <ul class="dropdown-menu">
        <li><a @click="create('basic')"><i class="fa fa-file-excel-o"></i> Basic Info</a></li>
        <li><a @click="create('funds')"><i class="fa fa-file-excel-o"></i> Funds &amp; Costs</a></li>
        <li><a @click="create('requirements')"><i class="fa fa-file-excel-o"></i> Travel Requirements</a></li>
        <li><a @click="create('travel')"><i class="fa fa-file-excel-o"></i> Travel Documents &amp; Itinerary</a></li>
        <li><a @click="create('rooming')"><i class="fa fa-file-excel-o"></i> Rooming</a></li>
        <li><a @click="create('itinerary')"><i class="fa fa-file-excel-o"></i> Itinerary</a></li>
        <li><a @click="create('medical')"><i class="fa fa-file-excel-o"></i> Medical Info</a></li>
      </ul>
    </div>
</template>
<script>
export default {
    name: 'reservation-reports',
    props: ['filters', 'search'],
    methods: {
        create(report) {
            let params = { 'author_id': this.$root.user.id };
            $.extend(params, this.filters);
            $.extend(params, {search: this.search});
            console.log(report);
            this.$http.post('reports/reservations/' + report, params).then(function (response) {
                this.$dispatch('showSuccess', response.body.message);
                this.report = '';
            }, function (error) {
                this.$dispatch('showError', 'Unable to create the report.');
            })
        }
    }
}
</script>