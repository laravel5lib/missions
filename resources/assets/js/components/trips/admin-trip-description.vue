<template>
    <div class="panel-body" style="position:relative">
        <spinner v-ref:spinner size="md" text="Loading"></spinner>
        <template v-if="editMode">
            <validator name="TripDescription">
                <form>
                    <div class="form-group" :class="{ 'has-error': checkForError('description') }">
                        <div class="row">
                            <div class="col-sm-12 text-right">
                                <button class="btn btn-primary btn-xs" type="button" @click="update">
                                    Update
                                </button>
                                <button class="btn btn-default-hollow btn-xs" type="button" data-toggle="modal" data-target="#markdownExamplesModal">
                                    Examples
                                </button>
                                <hr class="divider inv sm">
                            </div>
                            <div class="col-sm-12">
                                <textarea rows="5" v-autosize="description" v-model="description" class="form-control"
                                          v-validate:description="{ required: true}" placeholder="Please add a description" v-html="description"></textarea>
                            </div>
                        </div>
                    </div>
                    <hr class="divider inv">
                </form>
            </validator>
        </template>
        <template v-else>
            <div v-html="(description || 'No Description') | marked"></div>
        </template>

        <alert :show.sync="showSuccess" placement="top-right" :duration="3000" type="success" width="400px" dismissable>
            <span class="icon-ok-circled alert-icon-float-left"></span>
            <strong>Good job!</strong>
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
                editMode: false,
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
                // this.$refs.spinner.show();
                this.resource.update({ id: this.id }, {
                    description: this.description
                }).then(function (response) {
                    this.trip = response.data.data;
                    this.description = response.data.data.description;
                    // this.$refs.spinner.hide();
                    this.showSuccess = true;
                });
            }
        },
        ready(){
            this.resource.get({ id: this.id }).then(function (response) {
                // this.trip = response.data.data;
                this.description = response.data.data.description;
            });
            var self = this;
            this.$root.$on('toggleMode', function () {
                self.editMode = !self.editMode;
            });
        }
    }
</script>