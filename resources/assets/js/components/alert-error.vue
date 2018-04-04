<script>
export default {
    name: 'alert-error',

    render() {
        this.$root.$on('form:error', (data) => {
            swal(this.$slots.title[0].text, this.$slots.message[0].text, 'error');
        });
    }
}
</script>
