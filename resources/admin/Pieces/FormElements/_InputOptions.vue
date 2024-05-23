<template>
    <div class="fconnect_input_options">
        <el-select clearable filterable :multiple="field.is_multiple" :placeholder="field.placeholder" v-model="value">
            <el-option
                v-for="item in field.options"
                :key="item.id"
                :label="item.title"
                :value="item.id">
                <span style="float: left">{{ item.title }}</span>
                <span v-if="field.show_id" style="float: right; color: #8492a6; font-size: 13px">{{ item.id }}</span>
            </el-option>
        </el-select>
    </div>
</template>

<script type="text/babel">
export default {
    name: 'InputOptions',
    props: ['field', 'modelValue'],
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
