<script>
export default {

    name: 'price-push',

    props: {
        url: {
            type: String,
            default: false,
        },
        params: {
            type: Object
        }
    },

    data() {
        return {
            processing: false
        }
    },

    methods: {
        addPrice() {
            this.processing = true;
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
                    let that = this;
                    this.$http.post(this.url, this.params)
                        .then((response) => {
                            that.processing = false;
                            swal('Nice Work!', 'Price has been added.', 'success', {
                              buttons: false,
                              timer: 1000,
                            }).then((value) => {
                                window.location.reload();
                            })
                        });
                }
            })
        }
    },

    render() {
        return this.$scopedSlots.default({
            addPrice: this.addPrice,
            processing: this.processing
        })
    }
}
</script>