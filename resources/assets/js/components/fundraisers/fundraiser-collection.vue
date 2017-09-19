<template>
<div>
    <div class="panel panel-default" v-for="fundraiser in fundraisers">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-6"><h5>{{ fundraiser.name }}<br><small><label>Fundraiser Title</label></small></h5></div>
                <div class="col-xs-6">
                    <a :href="fundraiserPath(fundraiser)" class="btn btn-sm btn-primary pull-right" target="_blank">View</a>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12">
                    <fundraisers-manager :id="fundraiser.id" :sponsor-id="fundraiser.sponsor_id" :editable="1" @fundraiserSettingsChanged="fundraiserSettingsChanged"></fundraisers-manager>
                </div>
            </div>
        </div>
    </div>
    <markdown-example-modal></markdown-example-modal>
</div>
</template>
<script type="text/javascript">
    import fundraisersManager from './fundraisers-manager.vue';
    import markdownExampleModal from '../markdown-example-modal.vue';
    export default{
        name: 'fundraiser-collection',
        props: ['fundId'],
        components: {'fundraisers-manager': fundraisersManager, 'markdown-example-modal': markdownExampleModal},
        data(){
            return{
                fundraisers: []
            }
        },
        methods: {
            fundraiserPath(fundraiser) {
                return '/' + fundraiser.sponsor.data.url + '/' + fundraiser.url;
            },
            fetch() {
                this.$http.get('fundraisers/', { params: { fund: this.fundId, include: 'sponsor' } }).then((response) => {
                    this.fundraisers = response.data.data;
                }, (error) =>  {
                    this.$root.$emit('showError', 'Unable to retreive fundraisers.');
                });
            },
            fundraiserSettingsChanged(fundraiser) {
                _.findWhere(this.fundraisers, {id: fundraiser.id}).name = fundraiser.name;
                _.findWhere(this.fundraisers, {id: fundraiser.id}).url = fundraiser.url;
            }
        },
        mounted() {
            this.fetch();
        }
    }
</script>