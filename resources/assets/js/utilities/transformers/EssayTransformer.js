class EssayTransformer {
    constructor(document) {
        this.document = document;
    }

    transform() {
        let essay = {
            id: this.document.id,
            name: this.document.author_name,
            last_updated: moment(this.document.updated_at).format('ll')
        }

        _.each(this.document.content, function (item) {
            essay[item.q] = item.a;
        });

        return essay;
    }
}

export default EssayTransformer;