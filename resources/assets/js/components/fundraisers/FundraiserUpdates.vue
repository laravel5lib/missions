<template>
    <div>
        <template v-if="loadedStory">
            <h3>{{ loadedStory.title }}</h3>
            <p v-html="marked(loadedStory.content)"></p>
            <p><a @click="unloadStory()" class="text-primary">
                <i class="fa fa-caret-left"></i> More Updates</a>
            </p>
        </template>
        <template v-else>
            <template v-if="stories.length">
            <div v-for="story in stories" class="clearfix">
                    <h5 style="padding-bottom: 0; margin-bottom: 0">
                        <small class="label label-primary">{{ story.created_at|moment('ll') }}</small>
                    </h5>
                    <h3 style="padding-top: 0; margin-top: 1rem">
                        {{ story.title }}
                    </h3>
                    <p v-html="marked(story.preview)"></p>
                    <a class="text-primary" @click="loadStory(story)">Read More <i class="fa fa-caret-right"></i></a>
                    <hr class="divider lg inv">
            </div>
            <div class="text-center" v-if="pagination.total">
                <hr class="divider inv">
                <nav>
                    <ul class="pagination pagination-sm">
                        <li :class="{ 'disabled': pagination.current_page == pagination.total_pages }">
                            <a aria-label="Next" @click="loadMore">
                                <span aria-hidden="true">Load More</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            </template>
            <template v-else>
                <div class="text-center text-muted">
                    <h3>
                        <i class="fa fa-newspaper-o fa-5x"></i><br /><br />
                        No Updates<br />
                        <small>Come back later!</small>
                    </h3>
                </div>
            </template>
        </template>
    </div>
</template>
<script type="text/javascript">
let marked = require('marked');
export default {
    
    props: {
        fundraiserId: {
            type: String,
            required: true
        }
    },

    data () {
        return {
            stories: [],
            loadedStory: null,
            pagination: {
                current_page: 1,
                per_page: 10
            }
        };
    },
    watch: {
        loadedStory(val) {
          if (val) {
            // CTA bar height is 47px - gets in the way...
            // doubled value for better spacing
            const barOffset = 47 * 2;
            $('html, body').animate({scrollTop: ($('#updates').offset().top - barOffset) + 'px'}, 300);
          }

        }
    },

    methods: {
        
        marked: marked,

        loadMore() {
            if (this.pagination.current_page < this.pagination.total_pages) {
                this.pagination.current_page = this.pagination.current_page + 1;
            }

            this.getStories().then((response) => {
                this.stories = _.union(this.stories, response.data.data);
                this.pagination = response.data.meta.pagination;
            }, this.$root.handleApiError);
        },

        getStories() {
            return this.$http.get('stories', { params: {
                fundraiser: this.fundraiserId,
                page: this.pagination.current_page,
                per_page: this.pagination.per_page,
            }});
        },

        loadStory(story) {
            this.loadedStory = story;
        },

        unloadStory() {
            this.loadedStory = null;
        }

    },

    mounted() {
        this.loadMore();
    }
}
</script>
