<template>
<div class="panel panel-default" style="border-top: 5px solid #f6323e">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-3">
                <h5>Flights</h5>
            </div>
            <div class="col-sm-9 text-right">
                <form class="form-inline">
                    <label>Flight Segment:</label>
                    <select class="form-control input-sm" v-model="selectedSegment">
                        <option v-for="segment in segments" 
                                :key="segment.id" 
                                :value="segment.id"
                        >{{ segment.name }}
                        </option>
                    </select>
                    <div class="btn-group">
                        <button type="button" class="btn btn-link btn-sm dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-ellipsis-h"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li>
                                <a role="button" 
                                   data-toggle="modal" 
                                   data-target="#newSegment"
                                >Add New Segment</a>
                            </li>
                            <li role="separator" class="divider"></li>
                            <li :class="{ 'disabled' : !selectedSegment }">
                                <a role="button" 
                                   data-toggle="modal" 
                                   data-target="#editSegment" 
                                   :disabled="!selectedSegment"
                                >Edit Segment</a>
                            </li>
                            <li :class="{ 'disabled' : !selectedSegment }">
                                <a role="button" :disabled="!selectedSegment" @click="deleteSegment">Delete Segment</a>
                            </li>
                        </ul>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="panel-heading">
        <ul class="nav nav-pills">
            <li class="active"><a href="#">Booked <span class="badge badge-default">0</span></a></li>
            <li><a href="#">To be Booked <span class="badge badge-default">0</span></a></li>
            <li><a href="#">No Flight <span class="badge badge-default">0</span></a></li>
        </ul>
    </div>
    <div class="panel-body">
        <a role="button">+ Add a filter</a>
    </div>
    <div class="table-responsive">
    <table class="table">
        <thead>
            <tr class="active">
                <th><input type="checkbox"></th>
                <th>Surname</th>
                <th>Given Names</th>
                <th>Group</th>
                <th>Record</th>
                <th>Date</th>
                <th>Time</th>
                <th>IATA</th>
                <th>Flight</th>
            </tr>
        </thead>
    </table>
    </div>

    <div class="modal fade" id="newSegment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <ajax-form :action="'/campaigns/' + campaignId + '/flights/segments'" 
                            method="post" 
                            :horizontal="true" 
                            :initial="{name: ''}"
                            v-on:form:success="getSegments"
                    >
                    <template slot-scope="{ form }">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Add New Flight Segment</h4>
                        </div>
                        <div class="modal-body">
                            <input-text name="name" v-model="form.name" :horizontal="true" classes="col-sm-9">
                                <label slot="label" class="control-label col-sm-3">Segment Name</label>
                            </input-text>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary pull-right">Add</button>
                        </div>
                    </template>
                </ajax-form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editSegment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <ajax-form :action="'/campaigns/' + campaignId + '/flights/segments/' + this.selectedSegment"
                            ref="editForm" 
                            method="put" 
                            :horizontal="true" 
                            :initial="{name: ''}"
                            v-on:form:success="getSegments"
                    >
                    <template slot-scope="{ form }">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Rename Flight Segment</h4>
                        </div>
                        <div class="modal-body">
                            <input-text name="name" v-model="form.name" :horizontal="true" classes="col-sm-9">
                                <label slot="label" class="control-label col-sm-3">Segment Name</label>
                            </input-text>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary pull-right">Save Changes</button>
                        </div>
                    </template>
                </ajax-form>
            </div>
        </div>
    </div>

</div> 
</template>
<script>
export default {
    props: ['campaignId'],
    data() {
        return {
            segments: null,
            selectedSegment: null
        }
    },
    watch: {
        'selectedSegment'(val) {
            // get flights by segment
            this.$refs.editForm.form.name = _.findWhere(this.segments, {id: val}).name;
            this.$forceUpdate();
        }
    },
    methods: {
        getSegments() {
            $('#newSegment').modal('hide');
            $('#editSegment').modal('hide');

            this.$http.get(`/campaigns/${this.campaignId}/flights/segments`).then(response => {
                this.segments = response.data.data;
            });
        },
        deleteSegment() {
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
                    this.$http
                        .delete(`/campaigns/${this.campaignId}/flights/segments/${this.selectedSegment}`)
                        .then((response) => {
                            this.getSegments();
                        });
                }
            })
        }
    },
    mounted() {
        this.getSegments();
    }
}
</script>
