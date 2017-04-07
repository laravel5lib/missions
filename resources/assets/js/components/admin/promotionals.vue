<template>
        <component :is="currentView" 
                   :type="type" 
                   :id="id"
                   transition="fade" 
                   transition-mode="out-in">
        </component>
</template>
<style>
    .fade-transition {
        transition: opacity .3s ease;
    }
    .fade-enter, .fade-leave {
        opacity: 0;
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
          'type': {
            type: String,
            required: true
          },
          'id': {
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
            'load-view': function (view) {
                this.toView(view);
            }
        },
        methods: {
            toView(view){
                this.currentView = view;
            }
        }
    }
</script>