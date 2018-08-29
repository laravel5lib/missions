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
            if (item.type != 'file') {
                essay[item.q] = item.a;
            }
        });

        essay['files'] = 'n/a';

        if(this.document.uploads) {
            let files = _.map(this.document.uploads, function (upload) {
                return `<strong><a href="${upload.source}" target="_blank">${upload.name}</a></strong>`;
            });
            
            essay['files'] = files;
        }

        return essay;
    }
}

export default EssayTransformer;