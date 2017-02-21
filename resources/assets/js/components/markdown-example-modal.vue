<template>
	<div class="modal fade" id="markdownExamplesModal" tabindex="-1" role="dialog" aria-labelledby="markdownExamplesModal">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					Markdown Examples
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<tabs>
						<tab header="Syntax">
							<textarea class="form-control" id="exampleContent" v-model="content" rows="20"></textarea>
						</tab>
						<tab header="Preview">
							<div v-html="content | marked"></div>
						</tab>
					</tabs>
				</div>
			</div>
		</div>
	</div>
</template>
<style></style>
<script type="text/javascript">
    let marked = require('marked');
    export default{
        name: 'markdown-example-modal',
        data(){
            return {
                content: "#### Headers\n---\n# Header 1 \n## Header 2\n### Header 3\n#### Header 4\n##### Header 5\n\n---\n#### Lists\n---\n1. First ordered list item\n2. Another item\n⋅⋅* Unordered sub-list. \n1. Actual numbers don't matter, just that it's a number\n⋅⋅1. Ordered sub-list\n4. And another item.\n\n⋅⋅⋅You can have properly indented paragraphs within list items. Notice the blank line above, and the leading spaces (at least one, but we'll use three here to also align the raw Markdown).\n\n⋅⋅⋅To have a line break without a paragraph, you will need to use two trailing spaces.⋅⋅\n⋅⋅⋅Note that this line is separate, but within the same paragraph.⋅⋅\n⋅⋅⋅(This is contrary to the typical GFM line break behaviour, where trailing spaces are not required.)\n\n---\n### BlockQuotes\n---\n> Blockquotes are very handy in email to emulate reply text.\n> This line is part of the same quote."
            }
        },
        filters: {
            marked: marked,
        },
        methods: {},
        ready(){

        }
    }
</script>