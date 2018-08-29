<template>
    <div class="modal fade" id="addOnDemand" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Add to Existing</h4>
          </div>
          <div class="modal-body">
                <div class="checkbox" v-for="(option, index) in options" v-if="index === 0 || previousOptionWasSelected(index)">
                    <label>
                        <input type="checkbox" :value="option" v-model="selectedOptions" :disabled="selectedOptions[index+1]"> {{ option }}
                    </label>
                </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button class="btn btn-primary" @click="submit">Add Now</button>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
export default {
    props: {
        id: {
            type: String,
            required: true
        },
        url: {
            type: String,
            requried: true,
        },
        options: {
            type: Array,
            required: true
        }
    },

    data () {
        return {
            selectedOptions: []
        }
    },

    computed: {
        groups() {
            return _.contains(this.selectedOptions, 'groups');
        },
        trips() {
            return _.contains(this.selectedOptions, 'trips');
        },
        reservations() {
            return _.contains(this.selectedOptions, 'reservations');
        }
    },

    methods: {
        previousOptionWasSelected(index) {
            console.log(index);
            if (this.selectedOptions[index-1] == this.options[index-1]) {
                return true;
            }
        },

        submit() {
            swal('WARNING!', 'This action will add the requirement to all the selected items. This action cannot be easily undone!', 'warning', {
                closeOnClickOutside: true,
                buttons: {
                    cancel: {
                        text: "Cancel",
                        value: null,
                        visible: true,
                        closeModal: true,
                    },
                    confirm: {
                        text: "Add Requirement",
                        value: true,
                        visible: true,
                        closeModal: true
                    }
                },
                dangerMode: true
            }).then((value) => {
                if (value) {
                    let that = this;
                    this.$http.post(this.url, { 
                        groups: this.groups, 
                        trips: this.trips, 
                        reservations: this.reservations 
                    }).then((response) => {
                        swal('Nice Work!', 'Requirement has been added.', 'success', {
                          buttons: false,
                          timer: 1000,
                        }).then((value) => {
                            window.location.reload();
                        })
                    });
                }
            })
        }
    }
}
</script>