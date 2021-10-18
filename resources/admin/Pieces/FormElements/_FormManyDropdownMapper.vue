<template>
    <table class="fc_horizontal_table">
        <thead>
        <tr>
            <th>{{field.local_label}}</th>
            <th>{{field.remote_label}}</th>
            <th width="40px"></th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(item, itemIndex) in model" :key="itemIndex">
            <td>
                <el-select clearable filterable v-model="item.field_key" placeholder="Select Contact Property">
                    <el-option
                        v-for="(option, optionKey) in field.fields"
                        :key="optionKey"
                        :value="optionKey"
                        :label="option.label"></el-option>
                </el-select>
            </td>
            <td>
                <el-select clearable filterable v-model="item.field_value" placeholder="Select Form Property">
                    <el-option
                        v-for="option in field.value_options"
                        :key="option.id"
                        :value="option.id"
                        :label="option.title"></el-option>
                </el-select>
            </td>
            <td>
                <div class="text-align-right">
                    <el-button @click="deleteItem(itemIndex)" :disabled="model.length == 1" type="info" size="small" icon="el-icon-delete"></el-button>
                </div>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
            </td>
            <td>
                <div class="text-align-right">
                    <el-button @click="addMore()" size="small" icon="el-icon-plus">Add More</el-button>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script type="text/babel">
export default {
    name: 'FormManyDropdownMapper',
    props: ['field', 'model'],
    methods: {
        addMore() {
            this.model.push({
                field_key: '',
                field_value: ''
            })
        },
        deleteItem(index) {
            this.model.splice(index, 1);
        }
    }
};
</script>

<!--
Used IN:
/admin/Modules/Funnels/_Field.vue

-->
