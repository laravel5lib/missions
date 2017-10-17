Vue.mixin({
    methods: {
        // Some universal methods to replace vue 1 filters
        limitBy(arr, number) {
            return _.first(arr, number);
        },
        orderByProp(arr, prop, direction = 1) {
            if (!_.isArray(arr) || !arr.length) return [];
            let list = _.sortBy(arr, prop);
            return direction === -1 ? list.reverse() : list
        },
        currency(number, symbol = '$') {
            if (!isNaN(number)) {
                return symbol + (Number(number).toFixed(2));
            }
            return number
        },
        pluralize: (value, unit, suffix = 's') => `${value} ${unit}${value !== 1 ? suffix : ''}`,
        /*showSpinner(){
               this.$refs.spinner.show();
               },
               hideSpinner(){
               this.$refs.spinner.hide();
               },*/
    },
    computed: {
        firstUrlSegment() {
            return document.location.pathname.split("/").slice(1, 2).toString();
        },
        isAdminRoute() {
            return this.firstUrlSegment == 'admin';
        },
        isDashboardRoute() {
            return this.firstUrlSegment == 'dashboard';
        },
    },
    mounted() {
        function isTouchDevice() {
            return true == ("ontouchstart" in window || window.DocumentTouch && document instanceof DocumentTouch);
        }

        // Disable tooltips on all components on mobile
        if (isTouchDevice()) {
            $("[rel='tooltip']").tooltip('destroy');
        }

    }
});