export default {
    data() {
        return {
            activeFilters: []
        }
    },

    methods: {
        addActiveFilter(data) {
            this.removeActiveFilter(data.key);
            this.activeFilters.push(data);
        },
        removeActiveFilter(key) {
            if (_.findWhere(this.activeFilters, { key: key })) {
                this.activeFilters = _.reject(this.activeFilters, _.findWhere(this.activeFilters, { key: key }));
            }
        }
    }
}