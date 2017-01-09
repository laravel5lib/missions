<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Payments Due</h5>
        </div><!-- end panel-heading -->
        <div class="list-group" v-if="project">
            <div class="list-group-item" v-for="due in project.dues.data" :class="{'list-group-item-default': due.unsaved}">
                <div class="row">
                    <div class="col-md-3">
                        <label>Balance Due</label>
                        <p>{{ due.balance | currency }}</p>
                        <hr class="divider inv hidden-lg">
                    </div>
                    <div class="col-md-6">
                        <label>Cost</label>
                        <p>{{ due.cost }}</p>
                        <hr class="divider inv hidden-lg">
                    </div>
                    <div class="col-md-3">
                        <label>{{ due.due_at | moment 'll' }}</label>
                        <p>
                            <span class="badge" :class="{'badge-success': due.status === 'paid', 'badge-danger': due.status === 'late', 'badge-info': due.status === 'extended', 'badge-warning': due.status === 'pending' }">{{due.status|capitalize}}</span>
                        </p>
                        <hr class="divider inv hidden-lg">
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end panel -->
</template>

<script type="text/javascript">
    // inmport vSelect from 'vue-select';
    export default{
        name: 'project-dues',
        props: ['id'],
        // components:{ vSelect },
        data(){
            return{
                project: null,
                projectsDues:[],
                dues:[],
                selectedDues: [],
                availableDues: [],
                resource: this.$resource('projects/' + this.id, { include: 'dues,costs.payments,initiative.costs.payments' }),
                showAddModal: false,
                showNewModal: false,
                showEditModal: false,
                attemptSubmit: false,

                preppedProject : {}
            }
        },
        methods: {
            dateIsBetween(a, b){
                var start = b === 0 ? moment().startOf('month') : moment().add(1, 'month').startOf('month');
                var stop = b === 0 ? moment().endOf('month') : moment().add(1, 'month').endOf('month');
                console.log(moment(a).isBetween(start, stop));
                return moment(a).isBetween(start, stop);
            },
            isPast(date){
                return moment().isAfter(date);
            },
            setProjectData(project){
                this.project = project;
                this.preppedProject = {
                    name: this.project.name,
                    project_initiative_id: this.project.project_initiative_id,
                    sponsor_id: this.project.sponsor_id,
                    sponsor_type: this.project.sponsor_type,
                    plaque_prefix: this.project.plaque_prefix,
                    plaque_message: this.project.plaque_message,
                };

                // Extend dues data

            }
        },
        ready(){
            /*this.resource.get().then(function (response) {
             this.setProjectData(response.data.data)
             });*/

            //Listen to Event Bus
            this.$root.$on('Project:CostsUpdated', function (data) {
                this.setProjectData(data)
            }.bind(this));

            this.$root.$on('Project:CostsReverted', function (data) {
                this.setProjectData(data)
            }.bind(this));

        }
    }
</script>