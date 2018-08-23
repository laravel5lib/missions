<template>
    <div class="panel panel-default" style="border-top: 5px solid #f6323e">
        <div class="panel-heading"><h5>{{ docType | underscoreToSpace | titleCase }}</h5></div>
        <template v-if="documents.length && !selectedDocument">
                
            <!-- <div class="panel-body"> -->
                <div class="alert alert-warning" style="margin: 0; border-radius: 0;">Choose a {{ docType }} to use.</div>
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr class="active">
                                <th><i class="fa fa-cog"></i></th>
                                <th v-for="(value, key) in headings" v-if="key != 'id'">
                                    {{ key | underscoreToSpace | titleCase }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="document in mapRows(documents)" :class="{ 'text-danger': document.expired == 'Yes' }">
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
                                <td v-for="(value, key) in document" v-if="key != 'id'">
                                    {{ value }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <!-- </div> -->
            
            <div class="panel-body text-right">
                <button class="btn btn-link" @click="documents = []">Cancel</button>
                <a class="btn btn-primary" :href="`/${firstUrlSegment}/records/${slug}/create?reservation=${reservationId}&amp;requirement=${requirement.id}`">Add New {{ docType | underscoreToSpace | titleCase }}</a>
            </div>
    
        </template>
        <template v-else>

            <template v-if="selectedDocument">
                <div class="list-group">
                    <div class="list-group-item" v-for="(value, key) in selectedDocument" v-if="key != 'id'">
                        <div class="row">
                            <div class="col-xs-4 text-muted text-right">
                                {{ key | underscoreToSpace | titleCase }}
                            </div>
                            <div class="col-xs-8">
                                <ul v-if="isArray(value)">
                                    <li v-for="item in value">
                                        {{ item }}
                                    </li>
                                </ul>
                                <span v-else>{{ value }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body text-right" v-if="requirement.pivot.status != 'complete'">
                    <button @click="removeDocument(selectedDocument)" class="btn btn-sm btn-link">Choose a different {{ docType | underscoreToSpace }}</button>
                </div>
            </template>
            <template v-else>
                <div class="panel-body text-center">
                    <span class="lead">No {{ docType | underscoreToSpace | titleCase }} Found</span>
                    <p>Attach an existing {{ docType | underscoreToSpace }} or create a new one.</p>
                    <p><button class="btn btn-primary-hollow" @click="fetchDocuments">Add {{ docType | underscoreToSpace | titleCase }}</button></p>
                </div>
            </template>

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
            documents: []
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
            return _.first(this.mapRows(this.documents));
        }
    },

    methods: {
        isArray(value) {
            return _.isArray(value);
        },
        selectDocument(document) {

        },
        addDocument(document) {
            this.$http
                .post(`reservations/${this.reservationId}/${this.type}`, { 'document_id': document.id })
                .then((response) => {
                    this.selectedDocument = document;
                    swal('Nice Work!', 'Document has been added.', 'success');
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
            // replace underscores with dashes
            let type = this.type.replace(/_/g, '-');

            this.$http
                .get(`reservations/${this.reservationId}/${type}`)
                .then((response) => {
                    this.selectedDocument = _.first(this.mapRows(response.data.data));
                })
                .catch((error) => {
                    this.handleError(error)
                });
        },
        fetchDocuments() {
            // replace underscores with dashes
            let type = this.type.replace(/_/g, '-');

            this.$http
                .get(`${type}`)
                .then((response) => {
                    this.documents = response.data.data;
                })
                .catch((error) => {
                    this.handleError(error)
                });
        },
        mapRows(documents) {
            let that = this;
            return _.map(documents, function (document) {
                return new DocumentTransformer(document, that.type).get();
            })
        },
        handleError(error) {
            swal('Oops!', error, 'error');
        }
    },

    mounted() {
        // check if a document has already 
        // been attached and retrieve it
        this.fetchDocument();
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
</style>