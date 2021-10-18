<template>
    <el-select
        v-model="modelValue"
        filterable
        remote
        placeholder="Search"
        :remote-method="fetchData"
        :loading="loading">
        <el-option
            v-for="item in options"
            :key="item[value_selector]"
            :label="getLabel(item)"
            :value="item[value_selector]">
        </el-option>
    </el-select>
</template>

<script type="text/babel">
import each from 'lodash/each';

export default {
    name: 'RemoteSelector',
    props: ['api_path', 'response_key', 'value_selector', 'label_selectors', 'label_joiner', 'modelValue'],
    emits: ['update:modelValue'],
    watch: {
        modelValue(value) {
            this.$emit('update:modelValue', value);
        }
    },
    data() {
        return {
            options: [],
            loading: false
        }
    },
    methods: {
        fetchData(query) {
            if (!query) {
                query = this.modelValue;
            }

            if (query !== '') {
                this.loading = true;
                this.$get(this.api_path, {
                    search: query,
                    order_by: this.value_selector,
                    order_type: 'ASC'
                })
                .then(response => {
                    if(response[this.response_key].data) {
                        this.options = response[this.response_key].data;
                    }
                })
                .catch(errors => {
                    this.handleError(errors);
                })
                .always(() => {
                    this.loading = false;
                });
            } else {
                this.options = [];
            }
        },
        getLabel(item) {
            const labels = [];
            each(this.label_selectors, (selector) => {
                labels.push(item[selector]);
            });
            return labels.join(this.label_joiner);
        }
    },
    mounted() {
        this.fetchData(this.modelValue);
    }
}
</script>
