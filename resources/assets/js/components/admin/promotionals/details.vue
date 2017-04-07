<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-sm-8">
                    <h5>{{ promo.name }}</h5>
                </div>
                <div class="col-sm-4 text-right">
                    <a @click="callView('list')" class="btn btn-primary btn-sm">
                        <i class="fa fa-chevron-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
        <div class="panel-body">
        </div>
    </div>
</template>
<script type="text/javascript">
    export default {
        name: 'details',
        props: {
          'type': {
            type: String,
            required: false
          },
          'id': {
            type: String,
            required: false
          }
        },
        data() {
            return {
                promo: null
            }
        },
        events: {},
        methods: {
            callView(view) {
                this.$dispatch('load-view', view);
            }
        }
    }
</script>