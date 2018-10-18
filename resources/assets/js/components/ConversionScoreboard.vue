<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div style="display:flex; justify-content: space-between; align-items: center;">
                <h5>Top Converters for {{ month }}</h5>
                <a role="button" v-show="records.length > 4" @click="loadAll">View All</a>
            </div>
        </div>
        <div v-if="loading" style="min-height: 175px; display: flex; align-items: center; justify-content: center;">
            <p class="lead text-muted">Loading...</p>
        </div>
        <table class="table table-condensed table-striped" v-if="!loading && records.length" style="min-height: 175px;">
            <tbody>
                <tr  v-for="(record, index) in records">
                    <td class="text-muted">
                        {{ index+1 }}.
                    </td>
                    <td>
                        {{ record.user }}
                    </td>
                    <td><code>{{ record.conversion_count }}</code></td>
                </tr>
            </tbody>
        </table>
        <div class="panel-body" 
             v-if="!loading && !records.length" 
             style="min-height: 175px; display: flex; align-items: center; justify-content: center;"
        >
            <p class="lead text-muted">Not enough data</p>
        </div>
        

        <div class="modal fade" id="allrecords" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">All Conversions by User</h4>
              </div>
              <div class="modal-body">
                <table class="table table-condensed table-striped"">
                    <tbody>
                        <tr  v-for="(record, index) in allRecords">
                            <td class="text-muted">
                                {{ index+1 }}.
                            </td>
                            <td>
                                {{ record.user }}
                            </td>
                            <td><code>{{ record.conversion_count }}</code></td>
                        </tr>
                    </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

    </div>
</template>

<script>
export default {

  name: 'ConversionScoreboard',

  props: {
    url: {
        type: String,
        required: true
    },
    month: {
        type: String,
        required: true
    }
  },

  data() {
    return {
        records: [],
        allRecords: [],
        loading: true
    }
  },

  methods: {
    loadAll() {
        if (!this.allRecords.length) {
            this.$http.get(this.url).then(response => {
                this.allRecords = response.data.data;
                $('#allrecords').modal('show');
            });
        } else {
            $('#allrecords').modal('show');
        }
    }
  },

  mounted() {
    this.$http.get(this.url + '&limit=5').then(response => {
        this.records = response.data.data;
        this.loading = false;
    })
  } 
}
</script>