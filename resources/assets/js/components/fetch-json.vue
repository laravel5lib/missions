<script>
export default {
    name: 'fetch-json',

    props: ['url', 'parameters'],

    data() {
        return {
            json: null,
            loading: true,
            pagination: {},
            filters: this.parameters ? this.parameters : {}
        }
    },

    methods: {
        fetch: _.debounce(function (params = this.filters) {
            this.loading = true;
                this.$http.get(this.url, {params}).then((response) => {
                    this.json = response.data.data;
                    this.pagination = response.data.meta;
                    this.loading = false;
                });
        }, 100),
        addFilter(key, value) {
            this.filters[key] = value;
            this.$forceUpdate();
            this.fetch();
        },
        removeFilter(key) {
            delete this.filters[key];
            this.$forceUpdate();
            this.fetch();
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
            removeFilter: this.removeFilter,
            fetch: this.fetch,
        })
    }
}
</script>