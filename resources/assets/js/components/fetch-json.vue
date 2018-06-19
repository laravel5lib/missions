<script>
import expiringStorage from '../expiring-storage';
import { pick } from '../helpers';
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
        },
        cacheKey: { default: null },
        cacheLifetime: { default: 5 }
    },

    data() {
        return {
            json: [],
            loading: true,
            pagination: {
                page: 0,
                per_page: 25
            },
            filters: this.parameters ? this.parameters : {}
        }
    },

    computed: {
        storageKey() {
            return this.cacheKey
                ? `fetch-json-component.${this.cacheKey}`
                : `fetch-json-component.${window.location.host}${window.location.pathname}${this.cacheKey}`;
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

            this.saveState();

            this.$forceUpdate();
        },
        changePage(page) {
            let params = $.extend({page: page, per_page: this.pagination.per_page}, this.filters);
            this.fetch(params);
        },
        changePerPage(count) {
            let params = $.extend({per_page: count}, this.filters);
            this.fetch(params);
        },
        saveState() {
            expiringStorage.set(this.storageKey, pick(this.$data, ['filters']), this.cacheLifetime);
        },
        restoreState() {
            const previousState = expiringStorage.get(this.storageKey);

            if (previousState === null) {
                return;
            }

            this.filters = previousState.filters;

            this.saveState();
        }
    },

    created() {
        this.restoreState();
        this.fetch();

        this.$on('filter:removed', function() {
            this.saveState();
        });
        
        this.$on('filter:added', function() {
            this.saveState();
        });
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
            changePerPage: this.changePerPage,
            sortBy: this.sortBy
        })
    }
}
</script>