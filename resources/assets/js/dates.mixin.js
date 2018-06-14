// a list of computed dates
export default {
    computed: {
        startOfToday() {
            return moment().startOf('day').format();
        },
        endOfToday() {
            var time = moment().startOf('day');
            return time.clone().endOf('day').format();
        },
        startOfYesterday() {
            return moment().subtract(1, 'day').startOf('day').format();
        },
        endOfYesterday() {
            var time = moment().subtract(1, 'day').startOf('day');
            return time.clone().endOf('day').format();
        },
        startOfWeek() {
            return moment().startOf('week').format();
        },
        endOfWeek() {
            var time = moment().startOf('week');
            return time.clone().endOf('week').format();
        },
        startOfMonth() {
            return moment().startOf('month').format();
        },
        endOfMonth() {
            var time = moment().startOf('month');
            return time.clone().endOf('month').format();
        },
        startOfLastMonth() {
            return moment().subtract(1, 'month').startOf('month').format();
        },
        endOfLastMonth() {
            var time = moment().subtract(1, 'month').startOf('month');
            return time.clone().endOf('month').format();
        }
    }
}