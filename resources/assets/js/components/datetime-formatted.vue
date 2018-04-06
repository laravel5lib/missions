<script>
export default {
    name: 'datetime-formatted',

    props: {
        value: {
            type: String,
            required: true
        },
        format: {
            type: String,
            default: 'MMM DD, YYYY hh:mm a'
        }
    },

    render: function (createElement) {
        return createElement(
            'span',   // tag name
            moment(this.value).format(this.format)
        )
    },
}
</script>

