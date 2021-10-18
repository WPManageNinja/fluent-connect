<template>
    <input-popover :placeholder="field.placeholder" :data="smartcodes" v-model="model"/>
</template>

<script type="text/babel">
import InputPopover from '@/Pieces/InputPopover';
export default {
    name: 'InputTextPopper',
    props: ['field', 'value'],
    components: {
        InputPopover
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
