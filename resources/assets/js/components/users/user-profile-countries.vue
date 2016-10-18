`<template>
    <div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h5>Countries Visited</h5>
            </div><!-- end panel-heading -->
            <div class="panel-body">
                <h4>
                    <p v-for="accolade in accolades.items">
                        <span class="label label-primary">
                            <i class="fa fa-map-marker"></i> {{ country($country) }}
                        </span>
                    </p>
                </h4>
            </div><!-- end panel-body -->
        </div><!-- end panel -->

        <modal class="text-center" v-if="isUser()" :show.sync="deleteModal" title="Delete Country" small="true">
            <div slot="modal-body" class="modal-body text-center">Are you sure you want to delete this Country?</div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" @click='deleteModal = false,removeCountry(selectedCountry)'>Confirm</button>
            </div>
        </modal>
    </div>
</template>
<script>
    import VueStrap from 'vue-strap/dist/vue-strap.min'
    export default{
        name: 'user-profile-countries',
        components: {'modal': VueStrap.modal},
        props:['id', 'authId'],
        data(){
            return{
                accolades: [],
                deleteModal: false,
                resource: this.$resource('users{/id}/accolades')
            }
        },
        methods:{
            isUser(){
                return this.id === this.authId;
            },
            removeAccolade(country){

            },
            addAccolade(country){

            },
            getAccolades(){
                this.resource.get({id: this.id}).then(function (response) {
                    this.accolades = response.data.data;
                });
            }
        },
        ready(){
            this.getAccolades();
        }
    }
</script>
