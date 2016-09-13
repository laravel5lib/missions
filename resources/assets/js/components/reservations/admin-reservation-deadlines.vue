<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <div>
        <ul class="list-group">
            <li class="list-group-item" v-for="deadline in reservationsDeadlines">
                <h4 class="list-group-item-heading">
                    <i class="fa {{ isPast(deadline.due_at) ? 'fa-times text-danger' : 'fa-exclamation text-warning' }}"></i>&nbsp;
                    {{ deadline.name ? deadline.name : !deadline.cost_name ? deadline.cost_name : deadline.item  + ' Submission' }}
                    <small class="text-muted"><i class="fa fa-clock-o"></i> {{ deadline.due_at| moment 'll' }}</small>
                    <a class="pull-right btn btn-xs" @click="remove(deadline)"><i class="fa fa-times"></i></a>
                </h4>
            </li>
        </ul>

        <modal title="Add Deadlines" :show.sync="..." effect="fade" width="800">
            <div slot="modal-body" class="modal-body">
                <validator name="AddManager">
                    <form class="form-horizontal" novalidate>
                        <div class="form-group" :class="{ 'has-error': checkForError('user') }"><label
                                class="col-sm-2 control-label">User</label>
                            <div class="col-sm-10">
                                <v-select class="form-control" id="user" multiple :value.sync="selectedDeadlines" :options="users"
                                          :on-search="getUsers" label="name"></v-select>
                                <select hidden="" v-model="user_id" v-validate:deadline="{ required: true }" multiple>
                                    <option :value="deadline.id" v-for="deadline in deadlines">{{deadline.name}}</option>
                                </select></div>
                        </div>
                    </form>
                </validator>
            </div>
        </modal>
    </div>
</template>

<script>
    import vSelect from 'vue-select';
    import VueStrap from 'vue-strap/dist/vue-strap.min'
    export default{
        name: 'admin-reservation-deadlines',
        props: ['id'],
        components:{ vSelect, 'modal': VueStrap.modal},
        data(){
            return{
                reservationsDeadlines:[],
                deadlines:[],
                selectedDeadlines: [],

                resource: this.$resource('reservations/' + this.id, { include: 'deadlines' })
            }
        },
        methods: {
            isPast(date){
                return moment().isAfter(date);
            },
            remove(deadline){
                this.resource.delete()
            },
            getDeadlines(search, loading){
                loading(true);
                this.$http.get('users', {search: search}).then(function (response) {
                    this.users = response.data.data;
                    loading(false);
                });
            }
        },
        ready(){
            this.resource.get().then(function (response) {
                this.deadlines = response.data.data.deadlines.data
            });
        }
    }
</script>
