<template>
    <a @click="toView('promotional-form')" class="btn btn-primary">
        <i class="fa fa-plus"></i> New
    </a>
    <component :is="currentView" 
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
    import promotionalList from './promotional-list.vue';
    export default {
        name: 'promotionals',
        components: [promotionalList],
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
                currentView: 'promotional-list'
            }
        },
        methods: {
            toView(view){
                this.currentView = view;
            }
        }
    }
</script>