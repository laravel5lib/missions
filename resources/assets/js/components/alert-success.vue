<script>
export default {
    name: 'alert-success',

    props: {
        timer: {
            type: Number,
            default: null
        },
        redirect: {
            type: String,
            default: null
        },
        reload: {
            type: Boolean,
            default: false
        }
    },

    render() {
        this.$root.$on('form:success', (data) => {
            
            let buttons = {};

            if (this.$slots.cancel) {
                buttons.cancel = {
                    text: this.$slots.cancel[0].text,
                    value: false,
                    visible: true,
                    closeModal: true,
                }
            }

            if (this.$slots.confirm) {
                buttons.confirm = {
                    text: this.$slots.confirm[0].text,
                    value: true,
                    visible: true,
                    closeModal: true,
                }
            }

            let timer = this.timer ? this.timer : null;

            swal(this.$slots.title[0].text, this.$slots.message[0].text, 'success', {
                closeOnClickOutside: false,
                buttons: buttons,
                timer: timer
            }).then((value) => {
                if (value || timer) {
                    this.$emit('form:redirect', data)
                }

                if (this.timer) {
                    this.$emit('form:redirect', data)
                }
            })

        });

        this.$on('form:redirect', (data) => {
            if (this.reload) {
                window.location.reload();
            }

            if (this.redirect) {
                
                if (this.redirect.endsWith('/')) {
                    window.location.href = this.redirect + data.data.id
                } else {
                    window.location.href = this.redirect
                }
            }
        });
    }
}
</script>
