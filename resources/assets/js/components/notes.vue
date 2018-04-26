<template>
<div>
    <div class="row">
        <div class="col-xs-12" v-if="app.user.can.create_notes">
            <a role="button" @click="prepareNew">
                <strong v-if="! newMode"><i class="fa fa-plus"></i> New Note</strong>
                <strong v-if="newMode"><i class="fa fa-list-ul"></i> View All Notes</strong>
            </a>
        </div>
    </div>
    <hr class="divider">

    <div class="row" v-if="newMode || editMode">
        <div class="well">
            <form>
                <div class="form-group">
                    <label>Subject</label>
                    <input class="form-control" type="text" v-model="selectedNote.subject" placeholder="subject">
                </div>
                <div class="form-group">
                    <label>Content</label>
                    <textarea class="form-control" v-model="selectedNote.content" placeholder="content" rows="10"></textarea>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-offset-4 col-sm-4 col-xs-6">
                            <button class="btn btn-sm btn-link btn-block" @click="reset">Cancel</button>
                        </div>
                        <div class="col-sm-4 col-xs-6">
                            <button class="btn btn-sm btn-primary btn-block" @click="save">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div v-if="! newMode && ! editMode">
        <div class="row">
            <div class="col-xs-12">
                <div class="input-group input-group-sm">
                    <input type="text"
                            class="form-control"
                            v-model="search"
                            @keyup="debouncedSearch"
                            placeholder="Search notes by subject, author or content...">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                </div>
            </div>
        </div>
        <hr class="divider inv">
        <div class="well" v-for="note in notes">
            <h6 class="list-group-item-heading">{{ note.subject }}</h6>
            <p class="text-muted">{{ note.user.data.name }} &middot; {{ note.created_at | moment('lll') }} <span v-if="note.created_at != note.updated_at">(modified)</span></p>
            <p class="list-group-item-text">{{ note.content }}</p>
            <hr class="divider sm">
            <p class="text-right" style="margin-bottom: 0; padding-bottom: 0;">
                <strong>
                <a role="button"
                        @click="prepareEdit(note)"
                        v-if="note.user.data.id === user_id">
                    Edit
                </a> &middot;
                <a role="button"
                        @click="selectedNote.id = note.id,deleteModal = true"
                        v-if="note.user.data.id === user_id || app.user.can.delete_notes">
                    Delete
                </a>
                </strong>
            </p>
        </div>
        <div class="list-group-item text-center text-muted" v-if="notes.length < 1">
            <p class="lead">No notes available.</p>
        </div>
        <div class="list-group-item text-center" v-if="pagination.per_page < pagination.total">
            <nav>
                <ul class="pager">
                    <li :class="{ 'disabled': pagination.current_page === 1 }" class="previous">
                        <a aria-label="Previous" @click="page=pagination.current_page-1">
                            <span aria-hidden="true">&laquo; Newer</span>
                        </a>
                    </li>
                    <li>Viewing {{ pagination.count }} of {{ pagination.total }} Notes</li>
                    <li :class="{ 'disabled': pagination.current_page == pagination.total_pages }" class="next">
                        <a aria-label="Next" @click="page=pagination.current_page+1">
                            <span aria-hidden="true">Older &raquo; </span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <modal class="text-center" :value="deleteModal" @closed="deleteModal=false" title="Delete Note" :small="true">
        <div slot="modal-body" class="modal-body text-center">Delete this Note?</div>
        <div slot="modal-footer" class="modal-footer">
            <button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Keep</button>
            <button type="button" class="btn btn-primary btn-sm" @click='deleteModal = false,remove(selectedNote)'>Delete</button>
        </div>
    </modal>

</div>
</template>
<script>
    import $ from 'jquery';
    import _ from 'underscore'
    export default{
        name: 'notes',
        props: {
            'type': {
                type: String
            },
            'id': {
                type: String
            },
            'per_page': {
                type: Number,
                default: 5
            },
            'user_id': {
                type: String
            },
            'canModify': {
                type: Number,
                default: 0
            }
        },
        data() {
            return {
                notes: {},
                selectedNote: {
                    'id': null,
                    'subject': null,
                    'content': null,
                    'noteable_id': this.id,
                    'noteable_type': this.type
                },
                page: 1,
                pagination: { current_page: 1 },
                search: null,
                newMode: false,
                editMode: false,
                showSuccess: false,
                showError: false,
                deleteModal: false,
                message: null,
                app: MissionsMe
            }
        },
        watch : {
            'page'(val, oldVal) {
                this.fetch();
            },
            'per_page'(val, oldVal) {
                this.fetch();
            },
            'search'(val, oldVal) {
                this.page = 1;
            },
            'id'(val, oldVal) {
                this.selectedNote.noteable_id = val;
                this.fetch();
            }
        },
        computed: {
            userId() {
                return this.user_id ? this.user_id : this.$root.user.id;
            }
        },
        methods: {
            debouncedSearch: _.debounce(function () {
                this.fetch();
            }, 250),
            prepareNew() {
                this.newMode = ! this.newMode;
                this.editMode = false;
                this.selectedNote.id = null;
                this.selectedNote.subject = null;
                this.selectedNote.content = null;
            },
            prepareEdit(note) {
                this.newMode = false;
                this.editMode = true;
                this.selectedNote.id = note.id;
                this.selectedNote.subject = note.subject;
                this.selectedNote.content = note.content;
            },
            fetch() {
                var params = '?sort=created_at|desc&include=user&per_page=' + this.per_page + '&page=' + this.page;

                if (this.search) {
                    params = params + '&search=' + this.search;
                }

                if (this.type) {
                    params = params + '&type=' + this.type;
                }

                if (this.type && this.id) {
                    params = params + '|' + this.id;
                }

                this.$http.get('notes' + params).then((response) => {
                    this.notes = response.data.data;
                    this.pagination = response.data.meta.pagination;
                });
            },
            save() {
                if (this.editMode == true) {
                    this.edit();
                } else {
                    this.create();
                }
            },
            create() {
                if (event)
                    event.preventDefault();
                $.extend(this.selectedNote, {'user_id' : this.userId});

                this.$http.post('notes', this.selectedNote).then(() => {
                    this.reset();
                    this.fetch();
                    this.$root.$emit('showSuccess', 'Note created successfully.');
                }, () =>  {
                    this.$root.$emit('showError', 'There are errors on the form.');
                });
            },
            edit() {
                if (event)
                    event.preventDefault();
                this.$http.put('notes/' + this.selectedNote.id, this.selectedNote).then(() => {
                    this.reset();
                    this.fetch();
                    this.$root.$emit('showSuccess', 'Note saved successfully.');
                }, () =>  {
                    this.$root.$emit('showError', 'There are errors on the form.');
                });
            },
            reset() {
                if (event)
                    event.preventDefault();
                this.selectedNote.id = null;
                this.selectedNote.subject = null;
                this.selectedNote.content = null;
                this.newMode = false;
                this.editMode = false;
            },
            remove(note) {
                this.$http.delete('notes/' + note.id).then(() => {
                    // reset selected
                    this.reset();
                    // re-fetch list
                    this.fetch();
                    this.$root.$emit('showSuccess', 'Note deleted.');
                }, () =>  {
                    this.$root.$emit('showError', 'Unable to delete note.');
                });
            }
        },
        mounted() {
            this.fetch()
        }
    }
</script>