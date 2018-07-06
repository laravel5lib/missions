<script>
export default {

    name: 'price-push',

    props: {
        url: {
            type: String,
            default: false,
        }
    },

    methods: {
        addPrice() {
            swal('WARNING!', 'This action will add the price to all the trip\'s reservations. This action cannot be easily undone!', 'warning', {
                closeOnClickOutside: true,
                buttons: {
                    cancel: {
                        text: "Cancel",
                        value: null,
                        visible: true,
                        closeModal: true,
                    },
                    confirm: {
                        text: "Add Price",
                        value: true,
                        visible: true,
                        closeModal: true
                    }
                },
                dangerMode: true
            }).then((value) => {
                if (value) {
                    this.$http.post(this.url)
                        .then((response) => {
                            swal('Nice Work!', 'Price has been added.', 'success', {
                              buttons: false,
                              timer: 1000,
                            })
                        });
                }
            })
        }
    },

    render() {
        return this.$scopedSlots.default({
            addPrice: this.addPrice,
        })
    }
}
</script>