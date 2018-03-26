<template>
    <div class="panel-body" style="position:relative">
        <spinner ref="spinner" global size="md" text="Loading"></spinner>
        <template v-if="editMode">

                <form>
                    <div class="form-group" :class="{ 'has-error': errors.has('description') }">
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
                                          name="description" placeholder="Please add a description" v-validate="'required'" v-html="descriptionHTML"></textarea>
                            </div>
                        </div>
                    </div>
                    <hr class="divider inv">
                </form>

        </template>
        <template v-else>
            <div v-html="marked(description || 'No Description')"></div>
        </template>

        <alert v-model="showSuccess" placement="top-right" :duration="3000" type="success" width="400px" dismissable>
            <span class="icon-ok-circled alert-icon-float-left"></span>
            <strong>Good job!</strong>
            <p>Trip Description Updated!</p>
        </alert>
    </div>
</template>
<script type="text/javascript">
    let marked = require('marked');
    export default{
    	name: 'admin-trip-description',
        props: ['id'],
        data(){
            return {
                editMode: false,
                description: '',
                resource: this.$resource('trips{/id}'),
                showSuccess: false,
            }
        },
	    computed: {
            descriptionHTML() {
                return this.description;
            }
	    },
        methods: {
            update(){
                // this.$refs.spinner.show();
                this.resource.put({ id: this.id }, {
                    description: this.description
                }).then((response) => {
                    this.trip = response.data.data;
                    this.description = response.data.data.description;
                    // this.$refs.spinner.hide();
                    this.showSuccess = true;
                });
            },
            marked: marked
        },
        mounted(){
            let self = this;
            this.resource.get({ id: this.id }).then((response) => {
                // this.trip = response.data.data;
                this.description = response.data.data.description;
            });
            this.$root.$on('toggleMode', () =>  {
                self.editMode = !self.editMode;
            });
        }
    }
</script>