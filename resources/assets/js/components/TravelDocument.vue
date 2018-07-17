<template>
    <div class="panel panel-default" style="border-top: 5px solid #f6323e">
        <div class="panel-heading"><h5>Travel Document</h5></div>
        <template v-if="documents.length && !selectedDocument">
            
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
                        <tr v-for="document in mapRows(documents)" @click="selectedDocument = document">
                            <td v-for="(value, key) in document" v-if="key != 'id'">
                                {{ value }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="panel-body text-right">
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
                <div class="panel-body text-right">
                    <button @click="selectedDocument = null" class="btn btn-sm btn-link">Choose a Different {{ docType }}</button>
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
export default {
    props: {
        reservationId: {
            type: String,
            required: true
        },
        type: {
            type: String,
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
            return this.type.substring(0, this.type.length - 1);
        },
        headings() {
            return _.first(this.mapRows(this.documents));
        }
    },

    methods: {
        addDocument() {
            this.$http
                .post(`reservations/${this.reservationId}/${this.type}`, { 'document_id': this.document.id })
                .then((response) => {
                    console.log('yay! it worked!');
                })
                .catch((error) => {
                    console.log('oops! something went wrong.');
                });
        },
        removeDocument() {
            this.$http
                .delete(`reservations/${this.reservationId}/${this.type}/${this.document.id}`)
                .then((response) => {
                    console.log('yay! it worked!');
                })
                .catch((error) => {
                    console.log('oops! something went wrong.');
                });
        },
        fetchDocument() {
            let type = this.type.replace(/_/g, '-');
            this.$http
                .get(`reservations/${this.reservationId}/${type}`)
                .then((response) => {
                    this.selectedDocument = _.first(response.data.data);
                    console.log('yay! it worked!');
                })
                .catch((error) => {
                    console.log('oops! something went wrong.');
                });
        },
        fetchDocuments() {
            let type = this.type.replace(/_/g, '-');
            this.$http
                .get(`${type}`)
                .then((response) => {
                    this.documents = response.data.data;
                    console.log('yay! it worked!');
                })
                .catch((error) => {
                    console.log('oops! something went wrong.');
                });
        },
        mapRows(documents) {
            let that = this;
            return _.map(documents, function (document) {

                if (that.type == 'passports') {
                    return {
                        id: document.id,
                        name: document.given_names + ' ' + document.surname,
                        number: document.number,
                        nationality: document.birth_country_name,
                        citizenship: document.citizenship_name,
                        expiration: moment(document.expires_at).format('ll'),
                        expired: document.expired ? 'Yes' : 'No'
                    }
                }

                if (that.type == 'medical_releases') {
                    return {
                        id: document.id,
                        name: document.name,
                        last_updated: moment(document.updated).format('ll')
                    }
                }

            })
        }
    },

    mounted() {
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