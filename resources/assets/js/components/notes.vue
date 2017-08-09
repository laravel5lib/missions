<template>
    <div class="panel panel-default">

        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-6">
                    <h5>Notes</h5>
                </div>
                <div class="col-xs-6 text-right" v-if="canModify">
                    <button class="btn btn-primary btn-xs" @click="prepareNew">
                        <span v-if="! newMode">New <i class="fa fa-plus"></i></span>
                        <span v-if="newMode">List <i class="fa fa-list-ul"></i></span>
                    </button>
                </div>
            </div>
        </div>
        <div class="panel-body" v-if="newMode || editMode">
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
                            <button class="btn btn-sm btn-default btn-block" @click="reset">Cancel</button>
                        </div>
                        <div class="col-sm-4 col-xs-6">
                            <button class="btn btn-sm btn-primary btn-block" @click="save">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="list-group" v-if="! newMode && ! editMode">
            <div class="list-group-item">
                <div class="input-group input-group-md">
                    <input type="text"
                           class="form-control"
                           v-model="search"
                           debounce="250"
                           placeholder="Search notes by subject, author or content...">

                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                </div>
            </div>
            <div class="list-group-item" v-for="note in notes">
                <div class="row" v-if="notes.length > 0">
                    <div class="col-xs-8">
                        <h5 class="list-group-item-heading">{{ note.subject }}
                            <br> <small>
                                By {{ note.user.data.name }} &middot; {{ note.created_at | moment('llll') }}
                                <span v-if="note.created_at != note.updated_at">(modified)</span>
                            </small>
                        </h5>
                    </div>
                    <div class="col-xs-4 text-right" v-if="note.user.data.id == user_id">
                        <button class="btn btn-xs btn-link" @click="prepareEdit(note)">
                            <i class="fa fa-pencil"></i>
                        </button>
                        <button class="btn btn-xs btn-link" @click="selectedNote.id = note.id,deleteModal = true">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <hr>
                <p class="list-group-item-text">{{ note.content }}</p>
                <hr class="divider inv">
            </div>
            <div class="list-group-item text-center text-muted" v-if="notes.length < 1">
                <p class="lead">No notes available.</p>
            </div>
            <div class="list-group-item text-center" v-if="pagination.per_page < pagination.total">
                <nav>
                    <ul class="pager">
                        <li :class="{ 'disabled': pagination.current_page == 1 }" class="previous">
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

        <modal class="text-center" :value="deleteModal" @closed="deleteModal=false" title="Delete Note" small="true">
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
                message: null
            }
        },
        watch : {
            'page': function (val, oldVal) {
                this.fetch();
            },
            'per_page': function (val, oldVal) {
                this.fetch();
            },
            'search': function (val, oldVal) {
                this.page = 1;
                this.fetch();
            },
            'id': function (val, oldVal) {
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

                this.$http.get('notes' + params).then(function (response) {
                    this.notes = response.body.data;
                    this.pagination = response.body.meta.pagination;
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
                event.preventDefault();
                $.extend(this.selectedNote, {'user_id' : this.userId});

                this.$http.post('notes', this.selectedNote).then(function () {
                    this.reset();
                    this.fetch();
                    this.$root.$emit('showSuccess', 'Note created successfully.');
                }, function () {
                    this.$root.$emit('showError', 'There are errors on the form.');
                });
            },
            edit() {
                event.preventDefault();
                this.$http.put('notes/' + this.selectedNote.id, this.selectedNote).then(function () {
                    this.reset();
                    this.fetch();
                    this.$root.$emit('showSuccess', 'Note saved successfully.');
                }, function () {
                    this.$root.$emit('showError', 'There are errors on the form.');
                });
            },
            reset() {
                event.preventDefault();
                this.selectedNote.id = null;
                this.selectedNote.subject = null;
                this.selectedNote.content = null;
                this.newMode = false;
                this.editMode = false;
            },
            remove(note) {
                this.$http.delete('notes/' + note.id).then(function () {
                    // remove note from collection
                    this.notes.$remove(note.id);
                    // reset selected
                    this.reset();
                    // re-fetch list
                    this.fetch();
                    this.$root.$emit('showSuccess', 'Note deleted.');
                }, function () {
                    this.$root.$emit('showError', 'Unable to delete note.');
                });
            }
        },
        mounted() {
            this.fetch()
        }
    }
</script>