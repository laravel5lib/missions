<script>
export default {
    props: ['filters'],
    data() {
        return {
            filters: this.filters ? this.filters : {}
        }
    },
    render() {
        return this.$scopedSlots.default({
            appliedFilters: this.filters
        })
    }
}
</script>
