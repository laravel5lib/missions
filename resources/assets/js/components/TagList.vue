<template>
<div>
    <fetch-json :url="`tags?filter[type]=${tagType}`">
        <div class="panel panel-default" slot-scope="{json:tags, pagination, changePage}">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-6"><h5>{{ tagType | capitalize }} Tags</h5></div>
                    <div class="col-xs-6 text-right">
                        <button class="btn btn-primary btn-sm" @click="createTag"><i class="fa fa-plus"></i> New Tag</button>
                    </div>
                </div>
            </div>
            <div class="table-responsive" v-if="tags.length">
                <table class="table">
                    <thead>
                        <tr class="active">
                            <th>#</th>
                            <th>Name</th>
                            <th>Order</th>
                            <th class="text-right">Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(tag, index) in tags">
                            <td>{{ index+1 }}</td>
                            <td><span class="label label-filter">{{ tag.name | capitalize }}</span></td>
                            <td>{{ tag.order }}</td>
                            <td class="text-right">
                                <a role="button" @click="editTag(tag)">Edit</a> | 
                                <a role="button" class="text-primary" @click="deleteTag(tag)">Delete</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="panel-body text-center text-muted lead" v-else>
                <p>No tags found.</p>
            </div>
            <div class="panel-footer" v-if="pagination.total > pagination.per_page">
                <pager :pagination="pagination" :callback="changePage"></pager>
            </div>
        </div>
    </fetch-json>

    <div class="modal fade" tabindex="-1" role="dialog" id="tagModal">
      <div class="modal-dialog" role="document">
        <ajax-form :action="modal.action" :method="modal.method" :initial="{name: '', type: tagType}" ref="ajax">
            <div class="modal-content" slot-scope="{form}">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{ modal.label }}</h4>
              </div>
              <div class="modal-body">
                    <input-text name="name" v-model="form.name">
                        <label slot="label">Tag Name</label>
                    </input-text>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">{{ modal.button }}</button>
              </div>
            </div><!-- /.modal-content -->
        </ajax-form>
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

</div>
</template>

<script>
export default {

    props: {
        tagType: {
            type: String,
            required: true
        }
    },

    data() {
        return {
            modal: {
                action: `tags/${this.tagType}`,
                method: 'post',
                label: 'Add New Tag',
                button: 'Create'
            }
        }
    },

    methods: {
        editTag(tag) {
            this.$refs.ajax.form.name = tag.name
            this.modal = {
                action: `tags/${this.tagType}/${tag.id}`,
                method: 'patch',
                label: 'Edit Tag',
                button: 'Update'
            };
            $('#tagModal').modal('show');
        },
        createTag() {
            this.modal = {
                action: `tags/${this.tagType}`,
                method: 'post',
                label: 'Add New Tag',
                button: 'Create'
            };
            $('#tagModal').modal('show');
        },
        deleteTag(tag) {
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
                        text: 'Delete',
                        value: true,
                        visible: true,
                        closeModal: true
                    }
                },
                dangerMode: true
            }).then((value) => {
                if (value) {
                    this.$http.delete(`tags/${tag.id}`)
                        .then((response) => {
                            window.location.reload();
                        });
                }
            })
        }
    }
}
</script>

<style lang="css" scoped>
</style>