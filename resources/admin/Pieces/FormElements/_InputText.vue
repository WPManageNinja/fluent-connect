<template>
    <el-input :type="field.data_type" :placeholder="field.placeholder" v-model="modelValue">
    </el-input>
</template>

<script type="text/babel">
export default {
    name: 'InputText',
    props: ['field', 'modelValue', 'suggests'],
    emits: ['update:modelValue'],
    watch: {
        modelValue(value) {
            this.$emit('update:modelValue', value);
        }
    }
}
</script>
