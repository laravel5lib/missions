<template>
    <transition name="fade">
        <div class="modal fade in" tabindex="-1" role="dialog" style="display: block" v-if="showModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5><slot name="title"></slot>
                        <button type="button" class="close" @click="toggleModal">
                            <span>&times;</span>
                        </button></h5>
                    </div>
                    <div class="modal-body">
                        <slot></slot>
                    </div>
                    <div class="modal-footer">
                        <slot name="footer"></slot>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script type="text/javascript">
    export default{
        name: 'mm-modal',
        props: {
        },
        data(){
            return{
                showModal: false
            }
        },
        methods:  {
            toggleModal() {
                this.showModal = !this.showModal;
            }
        },
        mounted() {
            this.$nextTick(function () {
                this.$root.$on('open:add-cost-modal', this.toggleModal);
                this.$root.$on('close:add-cost-modal', this.toggleModal);
                this.$root.$on('form:success', this.toggleModal);
            }.bind(this));
        }
    }
</script>
