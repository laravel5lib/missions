<template>
	<div style="position: relative;">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                        <div class="col-xs-6">
                        <ul class="nav nav-pills">
                            <li class="active">
                                <a href="#tutorial" aria-controls="tutorial" role="tab" data-toggle="tab">Tutorial</a>
                            </li>
                            <li v-if="currentPlan">
                                <a href="#notes" aria-controls="notes" role="tab" data-toggle="tab">Notes</a>
                            </li>
                        </ul>
                        </div>
                        <div class="col-xs-6">
                            <h6><a data-toggle="collapse" href="#collapseHints" aria-expanded="true" aria-controls="collapseHints"><span class="pull-right"><i class="fa" :class="{ 'fa-chevron-up': toggleHintsCollapse, 'fa-chevron-down': !toggleHintsCollapse}"></i> <span v-text="toggleHintsCollapse ? 'hide' : 'show'"></span></span></a></h6>
                        </div>
                        </div>
                    </div><!-- end panel-heading -->
                    <div class="panel-body panel-collapse collapse in" id="collapseHints">
                        
                        <section class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="tutorial">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-4">
                                        <h5>Room management made simple</h5>
                                        <p class="small">Utilize this simple tool to assign your missionaries to rooms.</p>
                                    </div><!-- end col -->
                                    <div class="col-xs-12 col-sm-8">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <h5>Follow these simple steps</h5>
                                            </div>
                                            <div class="col-xs-12 col-sm-6">
                                                <p class="small"><strong>Step 1</strong> Select rooming plan.</p>
                                                <p class="small"><strong>Step 2</strong> Add rooms to your rooming plan.</p>
                                                <p class="small"><strong>Step 3</strong> Select your squad.</p>
                                            </div><!-- end col -->
                                            <div class="col-xs-12 col-sm-6">
                                                <p class="small"><strong>Step 4</strong> Assign a Room Leader.</p>
                                                <p class="small"><strong>Step 5</strong> Add Squad Members to rooms.</p>
                                                <p class="small"><strong>Step 6</strong> Create more rooms as needed!</p>
                                            </div><!-- end col -->
                                        </div><!-- end row -->
                                    </div><!-- end col -->
                                </div><!-- end row -->
                            </div>
                            <div role="tabpanel" class="tab-pane" id="notes" v-if="currentPlan">
                                <notes type="plans"
                                       :id="currentPlan.id"
                                       :user_id="userId"
                                       :per_page="5"
                                       :can-modify="isAdminRoute ? 1 : 0">
                                </notes>
                            </div>
                        </section>

                    </div><!-- end panel-body -->
                </div><!-- end panel -->
            </div><!-- end col -->
        </div><!-- end row -->
		<spinner v-ref:spinner size="sm" text="Loading"></spinner>
		<component :is="currentView" :user-id="userId" :group-id="groupId" transition="fade" transition-mode="out-in" keep-alive></component>
	</div>
</template>
<style></style>
<script type="text/javascript">
	import plans from './rooming-plans-list.vue';
	import manager from './rooming-manager.vue';
    import notes from '../notes.vue';
    export default{
        name: 'rooming-wizard',
        props: {
            userId: {
                type: String,
                required: false
            },
            groupId: {
                type: String,
                required: false
            },
            campaignId: {
                type: String,
                required: false
            }
        },
        data(){
            return {
                currentView: 'plans',
	            currentPlan: null,
                toggleHintsCollapse: true
            }
        },
        components: {
            plans: plans,
            manager: manager,
            notes: notes
        },
        events: {
            'rooming-wizard:plan-selected'(plan) {
				this.currentPlan = plan;
				this.currentView = 'manager';
				this.$root.$emit('plan-scope', plan);
            },
            'rooming-wizard:plan-selection'() {
				this.currentPlan = null;
				this.currentView = 'plans';
            },
        },
	    ready() {
            let self = this;
            this.$root.$on('campaign-scope', function (val) {
                this.campaignId = val ? val.id : '';
                this.$root.$emit('update-title', val ? val.name : '');
            }.bind(this));

            $('#collapseHints')
                .on('show.bs.collapse', function () {
                    self.toggleHintsCollapse = true;
                })
                .on('hide.bs.collapse', function () {
                    self.toggleHintsCollapse = false;
                });
        }
    }
</script>