<template>
    <form @submit.prevent="onSubmit()" 
          @keydown="form.errors.clear($event.target.name)" 
          :class="{'form-horizontal' : horizontal}"
    >
        <slot :form="form"></slot>
    </form>
</template>
<script>
export default {
    name: 'ajax-form',

    props: {
        horizontal: {
            type: Boolean,
            default: false,
        },
        initial: {
            type: Object,
            default: null
        },
        hiddenValues: {
            type: Object,
            default: null
        },
        method: {
            type: String,
            default: 'post'
        },
        action: {
            type: String,
            required: true
        },
        redirect: {
            type: String,
            required: false
        },
        alert: {
            type: Object,
            function () { 
                return {
                    buttons: {
                        cancel: {
                            text: "Keep Working",
                            value: null,
                            visible: true,
                            closeModal: true,
                        },
                        confirm: {
                            text: "Done",
                            value: true,
                            visible: true,
                            closeModal: false
                        }
                    }
                }
            }
        }
    },

    data() {
        return {
            form: new Form(this.initial ? this.initial : {})
        }
    },

    methods: {
        onSubmit(){
            this.form.submit(this.method, this.action)
                .then(data => {

                    if (this.method == 'post') {
                        this.form.reset();
                        this.$root.$emit('form:reset');
                        this.$forceUpdate();
                    }
                    
                    this.$root.$emit('form:success', data);
                    this.$emit('form:success', data);

                })
                .catch(error => {
                    
                    this.$root.$emit('form:error', error);

                });
        }
    }
}
</script>

