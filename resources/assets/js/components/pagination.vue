<template>
    <nav>
        <ul style="margin-bottom:5px;" class="pagination" v-if="pagination.total_pages > 0" :class="sizeClass">
            <li v-if="showPrevious()" :class="{ 'disabled' : pagination.current_page <= 1 }">
                <span v-if="pagination.current_page <= 1">
                    <span aria-hidden="true">{{ config.previousText }}</span>
                </span>

                <a v-if="pagination.current_page > 1 " :aria-label="config.ariaPrevious" @click.prevent="changePage(pagination.current_page - 1)">
                    <span aria-hidden="true">{{ config.previousText }}</span>
                </a>
            </li>
            <li v-for="num in array" :class="{ 'active': num === pagination.current_page }">
                <a @click.prevent="changePage(num)">{{ num }}</a>
            </li>
            <li v-if="showNext()" :class="{ 'disabled' : pagination.current_page === pagination.total_pages || pagination.total_pages === 0 }">
                <span v-if="pagination.current_page === pagination.total_pages || pagination.total_pages === 0">
                    <span aria-hidden="true">{{ config.nextText }}</span>
                </span>

                <a v-if="pagination.current_page < pagination.total_pages" :aria-label="config.ariaNext" @click.prevent="changePage(pagination.current_page + 1)">
                    <span aria-hidden="true">{{ config.nextText }}</span>
                </a>
            </li>
        </ul>
        <label class="text-center" style="display:block;margin-bottom:10px;" v-if="!hideTotal">{{ pagination.total }} total</label>
    </nav>
</template>
<script type="text/javascript">
    export default{
        name: 'pagination',
        props: {
            pagination: {
                type: Object,
                required: true
            },
            paginationKey: {
                type: String,
                required: true
            },
            callback: {
                type: Function,
                required: true
            },
            options: {
                type: Object
            },
            size: {
                type: String,
                default: 'small'
            },
            hideTotal: {
                type: Boolean,
                default: false
            }
        },
        data(){
            return {};
        },
        computed: {
            array () {
                if (this.pagination.total_pages <= 0) {
                    return [];
                }

                let from = this.pagination.current_page - this.config.offset;
                if (from < 1) {
                    from = 1;
                }

                let to = from + (this.config.offset * 2);
                if (to >= this.pagination.total_pages) {
                    to = this.pagination.total_pages;
                }

                let arr = [];
                while (from <=to) {
                    arr.push(from);
                    from++;
                }

                return arr;
            },
            config () {
                // console.log(this.pagination);
                return Object.assign({
                    offset: 3,
                    ariaPrevious: 'Previous',
                    ariaNext: 'Next',
                    previousText: '«',
                    nextText: '»',
                    alwaysShowPrevNext: false
                }, this.options);
            },
            sizeClass () {
                if (this.size === 'large') {
                    return 'pagination-lg';
                } else if(this.size === 'small') {
                    return 'pagination-sm';
                } else {
                    return '';
                }
            }
        },
        watch: {
            'pagination.per_page' (newVal, oldVal) {
                if (+newVal !== +oldVal) {
                    this.callback();
                }
            }
        },
        methods: {
            showPrevious () {
                return this.config.alwaysShowPrevNext || this.pagination.current_page > 1;
            },
            showNext () {
                return this.config.alwaysShowPrevNext || this.pagination.current_page < this.pagination.total_pages;
            },
            changePage (page) {
                // Sort of hacky way to paginate without having to alter a lot of files
                let parent = this.findParent(this.$parent);
                if (this.pagination.current_page === page) {
                    return;
                }
                parent[this.paginationKey].current_page = page;
                this.$emit('paginate', page);
                this.callback();
            },
            findParent(parent) {
                // conditionally recursive function that locates the parent containing the matching pagination object
                if (parent[this.paginationKey] && parent[this.paginationKey] === this.pagination)
                    return parent;
                return this.findParent(parent.$parent)
            }
        },
		mounted() {

        }
    }
</script>
