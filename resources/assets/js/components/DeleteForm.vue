<template>
<div>
    <div class="row">
        <div class="col-xs-12">
            <label>Enter the {{ matchKey }} to delete it</label>
            <div class="input-group">
                <input class="form-control" v-model="enteredValue">
                <span class="input-group-btn">
                    <button class="btn btn-md btn-primary" :disabled="enteredValue != matchValue" @click.prevent="destroy">Delete</button>
                </span>
            </div>
        </div>
    </div>
</div>
</template>
<script>
export default {
    name: 'delete-form',

    props: ['url', 'redirect', 'matchKey', 'matchValue'],

    data() {
        return { enteredValue: null }
    },

    methods: {
        destroy() {
            swal('WARNING!', 'Are you sure? This action cannot be undone!', 'warning', {
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
                    this.$http.delete(this.url)
                        .then((response) => {
                            window.location.href = this.redirect;
                        });
                }
            })
        }
    }
}
</script>