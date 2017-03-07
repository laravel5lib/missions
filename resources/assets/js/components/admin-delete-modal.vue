<template>
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ label }}</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-6"><a class="btn btn-sm btn-block btn-default" data-dismiss="modal">{{ cancel }}</a></div>
                        <div class="col-xs-6"><a @click="destroy()" class="btn btn-sm btn-block btn-primary">{{ action }}</a></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
    export default{
        name: 'admin-delete-modal',
        props: {
            'id': {
                type: String,
                required: true,
            },
            'resource': {
                type: String,
                required: true,
            },
            'label': {
                type: String,
                default: 'Delete'
            },
            'action': {
                type: String,
                default: 'Delete'
            },
            'cancel': {
                type: String,
                default: 'Keep'
            }
        },
        data(){
            return{

            }
        },
        methods: {
            destroy(){
                this.$http.delete(this.resource + 's/' + this.id).then(function (response) {
                    window.location.href = '/admin/' + this.resource + 's/' + this.id;
                }, function (error) {
                    this.dispatch('showError', 'Unable to ' + this.action);
                })
            }
        }
    }
</script>
