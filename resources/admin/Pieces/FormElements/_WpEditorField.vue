<template>
    <wp-base-editor :editorShortcodes="smartcodes" v-model="model" />
</template>

<script type="text/babel">

import WpBaseEditor from '../_wp_editor';

export default {
    name: 'WPEditorField',
    props: ['field', 'value'],
    components: {
        WpBaseEditor
    },
    data() {
        return {
            model: this.value,
            smartcodes: window.fcAdmin.globalSmartCodes
        }
    },
    watch: {
        model(value) {
            this.$emit('input', value);
        }
    }
}
</script>
