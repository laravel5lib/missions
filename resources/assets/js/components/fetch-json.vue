<script>
export default {
    name: 'fetch-json',

    props: ['url'],

    data() {
        return {
            json: null,
            loading: true,
            pagination: {},
            filters: {}
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
        },
        addFilter(key, value) {
            this.filters[key] = value;
            this.$forceUpdate();
        },
        removeFilter(key) {
            // this.filters = value
        }
    },

    created() {
        this.fetch();
    },

    render() {
        return this.$scopedSlots.default({
            json: this.json,
            loading: this.loading,
            pagination: this.pagination,
            filters: this.filters,
            addFilter: this.addFilter,
            removeFilter: this.removeFilter
        })
    }
}
</script>