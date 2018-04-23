<script>
export default {
    name: 'fetch-json',

    props: ['url'],

    data() {
        return {
            json: null,
            loading: true,
            pagination: {}
        }
    },

    methods: {
        fetch(params) {
            this.loading = true;
            this.$http.get(this.url, {params}).then((response) => {
                this.json = response.data.data;
                this.pagination = response.data.meta;
                this.loading = false;
            });
        }
    },

    created() {
        this.fetch();
    },

    render() {
        return this.$scopedSlots.default({
            json: this.json,
            loading: this.loading,
            pagination: this.pagination
        })
    }
}
</script>