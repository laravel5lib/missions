<template>
    <div>
        <component :is="currentView" 
                   :promoter-type="promoterType" 
                   :promoter-id="promoterId"
                   @load-view="toView"
                   @promotionalStatusChanged=""
                   :id="id"
                   transition="fade" 
                   transition-mode="out-in">
        </component>
    </div>
</template>
<style scoped>
    .fade-transition {
        transition: opacity .3s ease;
    }
    .fade-enter, .fade-leave {
        opacity: 0;
    }
    div.panel.panel-default {
        min-height: 200px;
    }
</style>
<script type="text/javascript">
    import list from './promotionals/list.vue';
    import createEdit from './promotionals/create-edit.vue';
    import details from './promotionals/details.vue';
    export default {
        name: 'promotionals',
        components: [list, createEdit, details],
        props: {
          'promoterType': {
            type: String,
            required: true
          },
          'promoterId': {
            type: String,
            required: true
          }
        },
        data() {
            return {
                currentView: 'list'
            }
        },
        events: {
            'load-view'(msg) {
                this.toView(msg);
            }
        },
        methods: {
            toView(data){
                this.currentView = data.view;
                this.id = data.id;
            }
        }
    }
</script>