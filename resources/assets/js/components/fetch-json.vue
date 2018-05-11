<script>
export default {
    name: 'fetch-json',

    props: {
        url: {
            type: String,
            required: true
        }, 
        parameters: {
            type: Object,
            required: false
        }
    },

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
            params = decodeURIComponent($.param( params ));
            this.loading = true;
                this.$http.get(this.url+'?'+params).then((response) => {
                    this.json = response.data.data;
                    this.pagination = response.data.meta;
                    this.loading = false;
                });
        }, 100),
        addFilter(key, value) {
            this.filters[key] = value;

            let params = $.extend({page: 1}, this.filters);
            this.fetch(params);

            this.$forceUpdate();
        },
        removeFilter(key) {
           delete this.filters[key];

            let params = $.extend({page: 1}, this.filters);
            this.fetch(params);

            this.$forceUpdate();
        },
        changePage(page) {
            let params = $.extend({page: page}, this.filters);
            this.fetch(params);
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
            changePage: this.changePage
        })
    }
}
</script>