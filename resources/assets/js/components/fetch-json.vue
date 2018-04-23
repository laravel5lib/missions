<script>
export default {
    name: 'fetch-json',

    props: ['url'],

    data() {
        return {
            json: null,
            loading: true
        }
    },

    methods: {
        fetch() {
            this.loading = true;
            this.$http.get(this.url).then((response) => {
                this.json = response.data.data;
                this.loading = false;
            });
        }
    },

    created() {
        this.fetch();
    },

    render() {
        return this.$scopedSlots.default({
            json: this.json,
            loading: this.loading
        })
    }
}
</script>