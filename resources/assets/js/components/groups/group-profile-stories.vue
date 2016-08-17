<template>
    <div class="panel panel-default" v-for="story in stories">
        <div class="panel-heading" role="tab" id="heading-{{ story.id }}">
            <h5>
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-{{ story.id }}" aria-expanded="true" aria-controls="collapseOne">
                    {{ story.title }}
                </a>
            </h5>
        </div>
        <div id="collapse-{{ story.id }}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-{{ story.id }}">
            <div class="panel-body">
                <h5 class="media-heading"><a href="#">{{ story.author }}</a> <small>published a story {{ story.updated_at|moment 'll' }}.</small></h5>
                {{ story.content }}
            </div>
        </div>
    </div></template>
<script>

    export default{
        name: 'group-profile-stories',
        props:['id'],
        data(){
            return{
                stories: [],
            }
        },
        ready(){
            this.$http.get('stories?group=' + this.id, {
            }).then(function(response) {
                this.stories = response.data.data;
            });
        }
    }
</script>
