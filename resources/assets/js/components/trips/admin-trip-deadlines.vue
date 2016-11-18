<template>
    <spinner v-ref:spinner size="md" text="Loading"></spinner>
    <div class="panel-body" v-for="deadline in deadlines">
        <div class="row">
            <div class="col-xs-8">
                <h5><a href="#">{{ deadline.name }}</a></h5>
                <h6><small>Enforced: {{ deadline.enforced ? 'Yes' : 'No' }}</small></h6>
            </div>
            <div class="col-xs-4 text-right">
                <h5><i class="fa fa-calendar"></i> {{ deadline.grace_period }} {{ deadline.grace_period > 1 ? 'days' : 'day' }}</h5>
                <h6><small>Grace Period: {{ deadline.grace_period }} {{ deadline.grace_period > 1 ? 'days' : 'day' }}</small></h6>
            </div>
        </div><!-- end row -->
        <hr class="divider">
    </div>
    <modal class="text-center" :show.sync="showDeletetModal" title="Delete Deadlone" small="true">
        <div slot="modal-body" class="modal-body text-center" v-if="selectedCost">Are you sure you want to delete {{ selectedCost.name }}?</div>
        <div slot="modal-footer" class="modal-footer">
            <button type="button" class="btn btn-default btn-sm" @click='deleteCostModal = false'>Cancel</button>
            <button type="button" class="btn btn-primary btn-sm" @click='deleteCostModal = false,remove(selectedCost)'>Confirm</button>
        </div>
    </modal>

</template>
<script type="text/javascript">
    export default{
    	name: 'admin-trip-deadlines',
        props: ['id', 'assignment'],
        data(){
            return{
                deadlines:[],
                showAddModal: false,
                showEditModal: false,
                showDeletetModal: false,
                resource: this.$resource('deadlines{/id}')
            }
        },
        methods:{
            checkForError(field){
                return this.$TripDeadlinesCreate[field.toLowerCase()].invalid && this.attemptedAddDeadline;
            },
            resetDeadline(){
                this.newDeadline = {
                    item: '',
                    item_type: '',
                    due_at: null,
                    grace_period: 0,
                    enforced: false,
                };
            },
            addDeadline(){
                this.attemptedAddDeadline = true;
                if(this.$TripDeadlinesCreate.valid) {
                    this.deadlines.push(this.newDeadline);
                    this.resetDeadline();
                    this.toggleNewDeadline = false;
                    this.attemptedAddDeadline = false;
                }
            },
            confirmRemove(deadline) {
                this.selectedDeadline = deadline;
                this.showDeleteModal = true;
            },
            remove(deadline){
                this.resource.delete({ id: deadline.id }).then(function (response) {
                    this.deadlines.$remove(deadline);
                    this.selectedDeadline = null;
                });
            },
            searchDeadlines(){
                this.$refs.spinner.show();
                this.resource.get({
                    assignment: this.assignment + '|' + this.id,
                    search: this.search,
                    sort: this.sort,
                }).then(function (response) {
                    this.deadlines = response.data.data;
                    this.$refs.spinner.hide()
                });
            },

        },
        ready(){
            this.searchDeadlines();
        }
    }
</script>