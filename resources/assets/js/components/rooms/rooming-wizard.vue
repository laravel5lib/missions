<template>
	<div style="position: relative;">
		<spinner v-ref:spinner size="sm" text="Loading"></spinner>
		<component :is="currentView" :user-id="userId" :group-id="groupId" transition="fade" transition-mode="out-in" keep-alive></component>
	</div>
</template>
<style></style>
<script type="text/javascript">
	import plans from './rooming-plans-list.vue';
	import manager from './rooming-manager.vue';
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
            }
        },
        components: {
            plans: plans,
            manager: manager,
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
            this.$root.$on('campaign-scope', function (val) {
                this.campaignId = val ? val.id : '';
                this.$root.$emit('update-title', val ? val.name : '');
            }.bind(this));

        }
    }
</script>