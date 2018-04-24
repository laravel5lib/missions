<template>
<div>
    <div class="row">
        <div class="col-xs-12">
            <label>Enter the cost name to delete it</label>
            <div class="input-group">
                <input class="form-control" v-model="name">
                <span class="input-group-btn">
                    <button class="btn btn-md btn-primary" :disabled="name != costName" @click.prevent="destroy">Delete</button>
                </span>
            </div>
        </div>
    </div>
</div>
</template>
<script>
export default {
    name: 'price-delete',

    props: ['priceableType', 'priceableId', 'id', 'costName'],

    data() {
        return { name: null }
    },

    methods: {
        destroy() {
            swal('WARNING!', 'This action will remove this price from any trips and reservations using it. This action cannot be undone!', 'warning', {
                closeOnClickOutside: true,
                buttons: {
                    cancel: {
                        text: "Keep",
                        value: null,
                        visible: true,
                        closeModal: true,
                    },
                    confirm: {
                        text: "Delete",
                        value: true,
                        visible: true,
                        closeModal: true
                    }
                },
                dangerMode: true
            }).then((value) => {
                if (value) {
                    this.$http.delete('/' + this.priceableType + '/' + this.priceableId + '/prices/' + this.id)
                        .then((response) => {
                            window.location.href = '/admin/' + this.priceableType + '/' + this.priceableId;
                        });
                }
            })
        }
    }
}
</script>