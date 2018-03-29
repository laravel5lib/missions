Vue.mixin({
  methods: {
    // Some universal methods to replace vue 1 filters
    limitBy(arr, number) {
      return _.first(arr, number);
    },
    orderByProp(arr, prop, direction = 1) {
      let list;
      if (!_.isArray(arr) || !arr.length) return [];
      if (prop.includes('.')) {
        list = _.sortBy(arr, (obj) => {
          let props = prop.split('.');
          let p = obj;
          for (let i in props) {
            if (p.hasOwnProperty(props[i]))
              p = p[props[i]];
            else break;
          }
          return p;
        })
      } else list = _.sortBy(arr, prop);
      return direction === -1 ? list.reverse() : list
    },
    currency(number, symbol = '$', currencyAbbreviation = '') {
      if (!isNaN(number)) {
        return `${symbol}${(Number(number).toFixed(2))} ${currencyAbbreviation}`;
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