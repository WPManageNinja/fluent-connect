<template>
    <el-input :type="field.data_type" :placeholder="field.placeholder" v-model="value">
    </el-input>
</template>

<script type="text/babel">
export default {
    name: 'InputText',
    props: ['field', 'modelValue', 'suggests'],
    emits: ['update:modelValue'],
    watch: {
        value(value) {
            this.$emit('update:modelValue', value);
        }
    },
    data() {
        return {
            value: this.modelValue
        }
    }
}
</script>
