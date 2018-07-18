<template>
    <div class="panel panel-default" style="border-top: 5px solid #f6323e">
        <div class="panel-heading"><h5>{{ docType | underscoreToSpace | titleCase }}</h5></div>
        <template v-if="documents.length && !selectedDocument">
                
                <div class="panel-body">
                    <div class="alert alert-warning">Select an existing {{ docType }} to use it.</div>
                </div>
                
                <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr class="active">
                            <th v-for="(value, key) in headings" v-if="key != 'id'">
                                {{ key | underscoreToSpace | titleCase }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="document in mapRows(documents)" @click="addDocument(document)">
                            <td v-for="(value, key) in document" v-if="key != 'id'">
                                {{ value }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="panel-body text-right">
                <button class="btn btn-link" @click="documents = []">Cancel</button>
                <button class="btn btn-primary">Add New {{ docType }}</button>
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
                                {{ value }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body text-right" v-if="requirement.pivot.status != 'complete'">
                    <button @click="removeDocument(selectedDocument)" class="btn btn-sm btn-link">Choose a different {{ docType }}</button>
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
            type: Object,
            required: true
        }
    },

    data () {
        return {
            selectedDocument: null,
            documents: []
        }
    },

    computed: {
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
        cursor: pointer;
    }
    th, td {
        white-space: nowrap;
    }
    .panel-heading {
        border-color: #e6e6e6;
    }
</style>