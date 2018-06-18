<template>
     <div class="dropdown" style="display: inline-block; margin-left: 1em;">
        <a class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-download"></i> Download</a>
        <ul class="dropdown-menu">
            <li class="dropdown-header">File Type</li>
            <li>
                <a type="button" @click="download('xlsx')">Excel</a>
            </li>
            <li>
                <a type="button" @click="download('csv')">CSV</a>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    props: {
        type: {
            type: String,
            required: true
        },
        params: {
            type: String,
            required: false
        },
        filters: {
            type: Object,
            required: false
        }
    },
    data() {
        return {
            format: 'xlsx',
            filterObj: this.filters ? this.filters : {}
        }
    },
    methods: {
        download(format) {
            this.format = format;

            swal("Give your report a name: (optional)", {
                content: "input",
                button: 'Download',
            })
            .then((value) => {
                if (value) {
                    $.extend(this.filterObj, {filename: value});
                }

                this.fetch(this.filterObj);
            });
        },
        fetch(params) {
            this.$http.get(`/reports/create/${this.type}/${this.format}?${this.params}`, {
                params,
                paramsSerializer: function(params) {
                    return decodeURIComponent($.param(params));
                }
            }).then((response) => {
                let report = response.data.data;

                swal("Nice Work!", "Your report is being created. It will be available soon under reports.", "success", {
                    buttons: {
                        cancel: {
                            text: "Okay",
                            value: null,
                            visible: true,
                            className: "",
                            closeModal: true,
                        },
                        confirm: {
                            text: "View Reports",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true
                        }
                    }
                }).then((value) => {
                    if (value) {
                        window.location.href = '/'+this.firstUrlSegment+'/reports';
                    }
                });
            }).catch((error) => {
                console.log(error);
            });
        }
    }
}
</script>
