<template>
    <div>
        <el-select :placeholder="field.placeholder" filterable allow-create v-if="appVars.verified_senders.length" v-model="model">
            <el-option v-for="email in appVars.verified_senders" :key="email" :value="email"></el-option>
        </el-select>
        <el-input v-else :type="field.data_type" :placeholder="field.placeholder" v-model="model"></el-input>
    </div>
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
    watch: {
        model(value) {
            this.$emit('input', value);
        }
    }
}
</script>
