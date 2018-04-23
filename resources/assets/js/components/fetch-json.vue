<script>
export default {
    name: 'fetch-json',

    props: ['url'],

    data() {
        return {
            json: null,
            loading: true
        }
    },

    created() {
        this.$http.get(this.url).then((response) => {
            this.json = response.data.data;
            this.loading = false;
        });
    },

    render() {
        return this.$scopedSlots.default({
            json: this.json,
            loading: this.loading
        })
    }
}
</script>