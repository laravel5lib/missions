<template>
    <spinner v-ref:spinner size="md" text="Loading"></spinner>
    <div class="panel-body">
        <validator name="TripDescription">
            <form>
                <div class="form-group" :class="{ 'has-error': checkForError('description') }">
                    <label for="description" class="col-sm-2 control-label">
                        Description
                        <button class="btn btn-default btn-xs" type="button" data-toggle="collapse" data-target="#markdownPrev" aria-expanded="false" aria-controls="markdownPrev">
                            Preview
                        </button>
                        <hr class="divider inv sm">
                        <button class="btn btn-primary btn-xs" type="button" @click="update">
                            Update
                        </button>

                    </label>
                    <div class="col-sm-10">
                        <div class="row">
                            <div class="col-sm-12">
                                    <textarea rows="5" v-model="description" class="form-control"
                                              v-validate:description="{ required: true}"></textarea>
                            </div>

                        </div>

                    </div>
                </div>
                <hr class="divider inv">
            </form>
        </validator>
        <div class="collapse" id="markdownPrev" v-html="description | marked"></div>
        <alert :show.sync="showSuccess" placement="top-right" :duration="3000" type="success" width="400px" dismissable>
            <span class="icon-ok-circled alert-icon-float-left"></span>
            <strong>Well Done!</strong>
            <p>Trip Description Updated!</p>
        </alert>
    </div>
</template>
<script type="text/javascript">
    var marked = require('marked');
    export default{
    	name: 'admin-trip-description',
        props: ['id'],
        data(){
            return {
                // trip: null,
                description: '',
                resource: this.$resource('trips{/id}'),
                showSuccess: false
            }
        },
        filters: {
            marked: marked,
        },
        methods: {
            checkForError(field){
                // if user clicked continue button while the field is invalid trigger error styles
                return this.$TripDescription[field.toLowerCase()].invalid && this.attemptedContinue
            },
            update(){
                // var trip = this.trip;
                // trip.description = this.description;

                this.$refs.spinner.show();
                this.resource.update({ id: this.id }, {
                    description: this.description
                }).then(function (response) {
                    this.trip = response.data.data;
                    this.description = response.data.data.description;
                    this.$refs.spinner.hide();
                    this.showSuccess = true;
                });
            }
        },
        ready(){
            this.resource.get({ id: this.id }).then(function (response) {
                // this.trip = response.data.data;
                this.description = response.data.data.description;
            })
        }
    }
</script>