<template>
    <div class="panel panel-default" style="border-top: 5px solid #f6323e">
        <template v-if="!selectedDocument">
            <template v-if="!fetched">
                <div class="panel-heading"><h5>{{ docType | underscoreToSpace | titleCase }}</h5></div>
                <div class="panel-body text-center">
                    <span class="lead">No {{ docType | underscoreToSpace | titleCase }} Found</span>
                    <p>Attach an existing {{ docType | underscoreToSpace }} or create a new one.</p>
                    <p><button class="btn btn-primary-hollow" @click="fetchDocuments">Add {{ docType | underscoreToSpace | titleCase }}</button></p>
                </div>
            </template>
            <template v-else>
                <div class="panel-heading"><h5>Find an existing {{ docType | underscoreToSpace }}:</h5></div>
                <div class="table-responsive" v-if="documents.length">
                    <table class="table table-striped">
                        <thead>
                            <tr class="active">
                                <th><i class="fa fa-cog"></i></th>
                                <th v-for="(value, key) in headings" v-if="keyIsAllowed(key)">
                                    {{ key | underscoreToSpace | titleCase }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="document in documents" :class="{ 'text-danger': document.expired == 'Yes' }">
                                <td>
                                    <div class="btn-group">
                                        <a role="button" class="btn btn-link btn-sm dropdown-toggle" data-toggle="dropdown">
                                            <i class="fa fa-ellipsis-h"></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <template v-if="document.expired != 'Yes' || !document.expired">
                                                <li>
                                                    <a role="button" @click="addDocument(document)">
                                                        Use this {{ docType | underscoreToSpace | titleCase }}
                                                    </a>
                                                </li>
                                                <li role="separator" class="divider"></li>
                                            </template>
                                            <li>
                                                <a :href="`/${firstUrlSegment}/records/${slug}/${document.id}/edit?reservation=${reservationId}&amp;requirement=${requirement.id}`">
                                                    Edit
                                                </a>
                                            </li>
                                            <li><a href="#">Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                                <td v-for="(value, key) in document" v-if="keyIsAllowed(key)">
                                    {{ value }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="panel-body" v-if="!documents.length">
                    <p class="lead text-center text-muted">No {{ type | underscoreToSpace }} found</p>
                    <hr class="divider">
                </div>
                <div class="panel-body text-right">
                    <button class="btn btn-link" @click="documents = [], fetched = false">Cancel</button>
                    <a class="btn btn-primary" :href="`/${firstUrlSegment}/records/${slug}/create?reservation=${reservationId}&amp;requirement=${requirement.id}`">Add New {{ docType | underscoreToSpace | titleCase }}</a>
                </div>
                <div class="panel-footer" v-if="pagination.total > pagination.per_page">
                    <pager :pagination="pagination" :callback="changePage"></pager>
                </div>
            </template>
        </template>
        <template v-else>
            <div class="panel-heading"><h5>{{ docType | underscoreToSpace | titleCase }}</h5></div>
            <div class="list-group">
                <div class="list-group-item" v-for="(value, key) in selectedDocument" v-if="key != 'id'">
                    <div class="row">
                        <div class="col-xs-4 text-muted text-right">
                            {{ key | underscoreToSpace | titleCase }}
                        </div>
                        <div class="col-xs-8">
                            <ul v-if="isArray(value)">
                                <li v-for="item in value" v-html="item"></li>
                            </ul>
                            <span v-else v-html="value"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-body text-right" v-if="requirement.pivot.status != 'complete'">
                <button @click="removeDocument(selectedDocument)" class="btn btn-sm btn-link">Choose a different {{ docType | underscoreToSpace }}</button>
            </div>
        </template>
    </div>
</template>

<script>
import DocumentTransformer from '../utilities/DocumentTransformer';

export default {
    props: {
        reservationId: {
            type: String,
            required: true
        },
        type: {
            type: String,
            required: true
        },
        requirement: {
            type: Object
        }
    },

    data () {
        return {
            selectedDocument: null,
            documents: [],
            pagination: {
                total: 0,
                per_page: 25
            },
            fetched: false
        }
    },

    computed: {
        slug() {
            return this.type.replace(/_/g, '-');
        },
        docType() {
            // make it singular
            return this.type.substring(0, this.type.length - 1);
        },
        headings() {
            // grab the first document so we can use it's keys as table headings
            return _.first(this.documents);
        }
    },

    methods: {
        isArray(value) {
            return _.isArray(value);
        },
        addDocument(document) {
            this.$http
                .post(`reservations/${this.reservationId}/${this.type}`, { 'document_id': document.id })
                .then((response) => {
                    this.selectedDocument = document;
                    swal('Nice Work!', 'Document has been added.', 'success', {
                      buttons: false,
                      timer: 3000,
                    }).then(window.location.reload());
                })
                .catch((error) => {
                    this.handleError(error)
                });
        },
        removeDocument(document) {
            // load a list of documents if empty
            if (!this.documents.length) {
                this.fetchDocuments();
            }

            this.$http
                .delete(`reservations/${this.reservationId}/${this.type}/${document.id}`)
                .then((response) => {
                    this.selectedDocument = null;
                })
                .catch((error) => {
                    this.handleError(error)
                });
        },
        fetchDocument() {
            let params = {};

            // replace underscores with dashes
            let type = this.type.replace(/_/g, '-');

            if (type == 'medical-releases') {
                params = {include: 'conditions,allergies'};
            }

            this.$http
                .get(`reservations/${this.reservationId}/${type}`, {params})
                .then((response) => {
                    this.selectedDocument = _.first(this.mapRows(response.data.data));
                })
                .catch((error) => {
                    this.handleError(error)
                });
        },
        fetchDocuments(params) {
            this.fetched = true;
            // replace underscores with dashes
            let type = this.type.replace(/_/g, '-');

            if (type == 'medical-releases') {
                params = $.extend(params, {include: 'conditions,allergies'});
            }

            if (this.firstUrlSegment != 'admin') {
                params = $.extend(params, {'filter[managed_by]': this.$root.user.id});
            }

            this.$http
                .get(type, {
                    params
                })
                .then((response) => {
                    this.documents = this.mapRows(response.data.data);
                    this.pagination = response.data.meta;
                })
                .catch((error) => {
                    this.handleError(error)
                });
        },
        changePage(page) {
            let params = {page: page, per_page: this.pagination.per_page};
            this.fetchDocuments(params);
        },
        mapRows(documents) {
            let that = this;
            return _.map(documents, function (document) {
                return new DocumentTransformer(document, that.type).get();
            })
        },
        handleError(error) {
            swal('Oops!', error, 'error');
        },
        keyIsAllowed(key) {
            return _.contains(['name', 'number', 'country', 'expired', 'last_updated', 'sent', 'replied', 'has_conditions', 'has_allergies'], key)
        }
    },

    mounted() {
        // check if a document has already 
        // been attached and retrieve it
        let params = {page: 1, per_page: this.pagination.per_page};
        this.fetchDocument(params);
    }
}
</script>

<style lang="css" scoped>
    tr:hover {
        background-color: #fcf8e3 !important;
    }
    th, td {
        white-space: nowrap;
    }
    .panel-heading {
        border-color: #e6e6e6;
    }
    .btn-group {  
        position:absolute;          
    }
</style>