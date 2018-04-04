<template>
	<div>
		<template v-if="previewMode">
			<div v-html="contentHTML"></div>
		</template>
		<template v-else>
			<div class="row bg-gray">
				<div class="col-xs-8">
                    <label>Text</label>
                    <span class="help-block">Enter some text.</span>
                </div>
				<div class="col-xs-4 text-right">
					<div class="btn-group">
						<button type="button" class="btn btn-xs btn-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fa fa-ellipsis-h" aria-hidden="true"></i>
						</button>
						<ul class="dropdown-menu dropdown-menu-right">
							<li><a v-if="!first" @click="moveUp">Move Up</a></li>
							<li v-if="!last"><a @click="moveDown">Move Down</a></li>
							<li role="separator" class="divider"></li>
							<li><a @click="remove">Remove Text</a></li>
						</ul>
					</div>
				</div>
            </div>
            <div class="row bg-hollow">
                <div class="col-xs-12">
			         <textarea class="form-control" v-autosize="localContent" v-model="localContent" placeholder="Write content here..." rows="3" style="resize: vertical;"></textarea>
                </div>
            </div>
		</template>

	</div>
</template>
<style></style>
<script type="text/javascript">
//	import marked from 'marked';
    export default {
        name: 'text',
        props: {
          id: { type: String, default: _.uniqueId('text_') },
          previewMode: {
                type: Boolean,
                required: true
            },
            content: {
                type: String,
                required: true
            },
            drag: {
                type: Boolean,
                required: false
            },
            last: {
                type: Boolean,
                required: false
            },
            first: {
                type: Boolean,
                required: false
            },
        },
        data() {
            return {
                localContent: null,
            }
        },
        watch: {
            localContent(val, oldVal) {
                this.$emit('update:content', val)
            }
        },
        computed: {
            contentHTML() {
                let content = this.content;
//                content.replace(/\n/, /\n\r/);
                return content;
            }
        },
        methods: {
//            marked: marked,
            moveUp(){
                this.$emit('move-up')
            },
            moveDown(){
                this.$emit('move-down');
            },
            remove(){
                this.$emit('remove');
            },

        },
        mounted() {
            this.localContent = this.content;
        }
    }
</script>