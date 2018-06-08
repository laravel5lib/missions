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
            json: [],
            loading: true,
            pagination: {},
            filters: this.parameters ? this.parameters : {}
        }
    },

    methods: {
        fetch: _.debounce(function (params = this.filters) {
            this.loading = true;
                this.$http.get(this.url, {
                    params,
                    paramsSerializer: function(params) {
                        return decodeURIComponent($.param(params));
                    }
                }).then((response) => {
                    this.json = response.data.data;
                    this.pagination = response.data.meta;
                    this.loading = false;
                });
        }, 100),
        addFilter(key, value) {

            // temporary check until API is updated to match JSON-API spec
            if (this.filters.filter) {
                this.filters.filter[key] = value;
            } else {
                this.filters[key] = value;
            }

            let params = $.extend({page: 1}, this.filters);
            this.fetch(params);

            this.$emit('filter:added', {key: value});

            this.$forceUpdate();
        },
        removeFilter(key) {
            if (this.filters.filter) {
                delete this.filters.filter[key];
            } else {
                delete this.filters[key];
            }

            let params = $.extend({page: 1}, this.filters);
            this.fetch(params);

            this.$emit('filter:removed', key);

            this.$forceUpdate();
        },
        sortBy(column) {
            if(column === this.filters.sort) {
                this.filters.sort = '-'+column;
            } else {
                this.filters.sort = column;
            }

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
            changePage: this.changePage,
            sortBy: this.sortBy
        })
    }
}
</script>