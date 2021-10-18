<template>
    <div>
        <el-select :multiple="field.is_multiple" clearable filterable :placeholder="field.placeholder" v-model="modelValue">
            <el-option-group
                v-for="group in field.options"
                :key="group.label"
                :label="group.label"
            >
                <el-option
                    v-for="item in group.options"
                    :key="item.id"
                    :label="item.title"
                    :value="item.id">
                    <span style="float: left">{{ item.title }}</span>
                    <span v-if="field.show_id" style="float: right; color: #8492a6; font-size: 13px">{{ item.id }}</span>
                </el-option>
            </el-option-group>
        </el-select>
    </div>
</template>

<script type="text/babel">
export default {
    name: 'InputOptions',
    props: ['field', 'modelValue'],
    emits: ['update:modelValue'],
    watch: {
        modelValue(value) {
            this.$emit('update:modelValue', value);
        }
    }
}
</script>
