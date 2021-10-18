<template>
    <div class="wp_vue_editor_wrapper">
        <textarea v-if="hasWpEditor" class="wp_vue_editor" :id="editor_id">{{ modelValue }}</textarea>
        <textarea v-else
                  class="wp_vue_editor wp_vue_editor_plain"
                  v-model="plain_content"
                  @click="updateCursorPos">
        </textarea>
    </div>
</template>

<script type="text/babel">

export default {
    name: 'wp_editor',
    props: {
        editor_id: {
            type: String,
            default() {
                return 'wp_editor_' + Date.now() + parseInt(Math.random() * 1000);
            }
        },
        modelValue: {
            type: String,
            default() {
                return '';
            }
        },
        editorShortcodes: {
            type: Array,
            default() {
                return []
            }
        },
        height: {
            type: Number,
            default() {
                return 250;
            }
        },
        mediaButtons: {
            type: Boolean,
            default() {
                return true;
            }
        },
        autofocus: {
            type: Boolean,
            default() {
                return false;
            }
        }
    },
    emits: ['update:modelValue'],
    data() {
        return {
            showButtonDesigner: false,
            hasWpEditor: (!!window.wp.editor && !!wp.editor.autop) || !!window.wp.oldEditor,
            editor: window.wp.oldEditor || window.wp.editor,
            plain_content: this.modelValue,
            cursorPos: (this.modelValue) ? this.modelValue.length : 0,
            app_ready: false,
            buttonInitiated: false,
            currentEditor: false
        }
    },
    watch: {
        plain_content() {
            this.$emit('update:modelValue', this.plain_content);
        }
    },
    methods: {
        initEditor() {
            if (!this.hasWpEditor) {
                return;
            }

            this.editor.remove(this.editor_id);
            const that = this;
            const mceConfig = {
                height: that.height,
                toolbar1: 'formatselect,code,table,bold,italic,bullist,numlist,link,blockquote,alignleft,aligncenter,alignright,underline,strikethrough,forecolor,removeformat,codeformat,outdent,indent,undo,redo',
                setup(editor) {
                    editor.on('change', function (ed, l) {
                        that.changeContentEvent();
                    });
                }
            };

            if (this.autofocus) {
                mceConfig.auto_focus = this.editor_id;
            }

            this.editor.initialize(this.editor_id, {
                mediaButtons: this.mediaButtons,
                tinymce: mceConfig,
                quicktags: true
            });

            jQuery('#' + this.editor_id).on('change', function (e) {
                that.changeContentEvent();
            });
        },
        insertHtml(content) {
            this.currentEditor.insertContent(content);
        },
        changeContentEvent() {
            const content = this.editor.getContent(this.editor_id);
            this.$emit('update:modelValue', content);
        },

        handleCommand(command) {
            if (this.hasWpEditor) {
                window.tinymce.activeEditor.insertContent(command);
            } else {
                var part1 = this.plain_content.slice(0, this.cursorPos);
                var part2 = this.plain_content.slice(this.cursorPos, this.plain_content.length);
                this.plain_content = part1 + command + part2;
                this.cursorPos += command.length;
            }
        },

        updateCursorPos() {
            var cursorPos = jQuery('.wp_vue_editor_plain').prop('selectionStart');
            this.cursorPos = cursorPos;
        }
    },
    mounted() {
        this.initEditor();
        this.app_ready = true;
    }
}
</script>
<style lang="scss">
.wp_vue_editor {
    width: 100%;
    min-height: 100px;
}

.wp_vue_editor_wrapper {
    position: relative;

    .wp-media-buttons .insert-media {
        vertical-align: middle;
    }

    .popover-wrapper {
        z-index: 2;
        position: absolute;
        top: 0;
        right: 0;

        &-plaintext {
            left: auto;
            right: 0;
            top: -32px;
        }
    }

    .wp-editor-tabs {
        float: left;
    }
}

.mce-fluentcrm_editor_btn {
    button {
        font-size: 10px !important;
        border: 1px solid gray;
        margin-top: 3px;
    }

    &:hover {
        border: 1px solid transparent !important;
    }
}
</style>
