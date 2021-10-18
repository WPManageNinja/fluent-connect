<template>
    <el-checkbox-group v-model="model">
        <el-checkbox v-for="(option, optionKey) in field.options"
                     :key="optionKey"
                     :label="optionKey">
            {{option}}
        </el-checkbox>
    </el-checkbox-group>
</template>

<script type="text/babel">
export default {
    name: 'InputText',
    props: ['field', 'value'],
    data() {
        return {
            model: this.value
        }
    },
    emits: ['input'],
    watch: {
        model(value) {
            this.$emit('input', value);
        }
    }
}
</script>
