Vue.filter('phone', (phone) => {
    phone = phone || '';
    if (phone === '') return phone;
    return phone.replace(/[^0-9]/g, '')
        .replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
});

Vue.filter('number', (number, decimals) => {
    return isNaN(number) || number === 0 ? number : number.toFixed(decimals);
});

Vue.filter('percentage', (number, decimals) => {
    return isNaN(number) || number === 0 ? number : number.toFixed(decimals) + '%';
});

/**
 * Vue filter to convert the given value to whole dollars, no change.
 * http://jsfiddle.net/bryan_k/2t6bqqfc/
 *
 * @param {String} value The value string.
 */
/*Vue.filter('currency', (value, symbol) => {
  if(!value) {
    value = 0;
  }

  if(!symbol) {
    symbol = '$';
  }

  value = value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',').split('.')[0];
  if (symbol)
    return value;
  return symbol + value;
});*/

/**
 * MVC concept of handling dates.
 * Model/Server -> UTC | Vue Model/Controller -> UTC | View/Template -> Local
 * This filter should convert date assigned property from UTC to local
 * when being displayed -> read(). This filter should convert date assigned
 * property from Local to UTC when being changed via input -> writer5
 */
Vue.filter('moment', (val, format, diff = false, noLocal = false) => {
    if (!val) return val;

    if (noLocal) {
        return moment(val).format(format || 'LL'); // do not convert to local
    }

    // console.log('before: ', val);
    let date = moment.utc(val, 'YYYY-MM-DD HH:mm:ss').local().format(format || 'LL');

    if (diff) {
        date = moment.utc(val).local().fromNow();
    }

    return date;
});

Vue.filter('mDateToDatetime', (value, format) => {
    return moment.utc(value).local().format(format || 'LL');
});

Vue.filter('mUTC', (value) => {
    return moment.utc(value);
});

Vue.filter('mLocal', (value) => {
    return moment.isMoment(value) ? value.local() : null;
});

Vue.filter('mFormat', (value, format) => {
    return moment(value).format(format);
});

Vue.filter('underscoreToSpace', (value) => {
    return value.replace(/_/g, ' ');
});

Vue.filter('capitalize', (string) => {
    if (!string) return string;
    if (!_.isString(string)) string = string.toString();
    return string.replace(/\w\S*/g, function(txt){
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    });
});

Vue.filter('titleCase', function (string) {  
    if (!string) return string;
    if (!_.isString(string)) string = string.toString();
    return string.split(' ').map(function (txt) {
        return txt.charAt(0).toUpperCase() + txt.substring(1);
    }).join(' ');
});