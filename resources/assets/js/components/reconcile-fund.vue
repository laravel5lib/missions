<template>
    <div>
        <btn class="btn btn-sm btn-default" @click="reconcile"><i class="fa fa-calculator"></i> Reconcile</btn>
        <alert :show="showSuccess" placement="top-right" :duration="3000" type="success" width="400px" dismissable>
            <span class="icon-ok-circled alert-icon-float-left"></span>
            <strong>Done</strong>
            <p>Fund reconciled</p>
        </alert>
    </div>
</template>
<script>
    export default{
        name: 'reconcile-fund',
        props: ['id'],
        data(){
            return {
                showSuccess: false
            }
        },
        methods: {
            reconcile() {
                this.$http.put('funds/' + this.id + '/reconcile').then((response) => {
                    this.showSuccess = true;
                });
            }
        }
    }
</script>