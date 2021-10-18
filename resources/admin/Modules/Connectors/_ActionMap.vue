<template>
    <div v-loading="loading" class="fcon_trigger_view">
        <div @click="action.is_open = !action.is_open" class="fcon_provider_info">
            <div class="fc_trigger_logo">
                <img v-if="provider_info" :src="provider_info.logo" />
            </div>
            <div class="fc_trigger_titles">
                <span class="fc_trigger_name">{{action.title}}</span>
            </div>
            <div class="fc_trigger_actions">
                <el-tag size="mini">{{action.status}}</el-tag>
                <span class="fc_item_open"><i class="el-icon el-icon-arrow-down"></i></span>
            </div>
        </div>
        <div class="fcon_trigger_details">
            <div v-if="action.is_open" class="fcon_trigger_editor">
                <h3>Select Action Event</h3>
                <el-select @change="triggerEventChanged()" placeholder="Select Integration Event"
                           v-model="action.action_name">
                    <el-option
                        v-for="(triggerItem, triggerName) in provider_info.actions"
                        :key="triggerName"
                        :value="triggerName"
                        :label="triggerItem.title"
                    />
                </el-select>
                <div v-if="action.action_name">
                    <form-builder :fields="settings_fields" :form-data="action.settings" />

                    <div class="el-form-item__content">
                        <label>Internal Label</label>
                        <el-input placeholder="Internal Label" v-model="action.title"></el-input>
                    </div>

                    <el-checkbox v-model="action.status" true-label="published" false-label="draft">Activate this Action</el-checkbox>

                    <div style="display: block; margin-top: 10px;">
                        <el-button @click="emitSave()" size="small" type="success">Save</el-button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
import FormBuilder from '../../Pieces/FormElements/_FormBuilder';
export default {
    name: 'ActionMap',
    props: ['action', 'provider_info'],
    components: {
        FormBuilder
    },
    data() {
        return {
            settings_fields: {},
            loading: false
        }
    },
    methods: {
        triggerEventChanged() {
            if (!this.action.action_name) {
                this.action.settings = {};
                return false;
            }
            const selectedAction = JSON.parse(JSON.stringify(this.provider_info.actions[this.action.action_name]));
            this.action.settings = selectedAction.settings_defaults;
            this.action.title = selectedAction.title;
            this.fetchSettingsFields();
        },
        fetchSettingsFields() {
            this.loading = true;
            this.$get('feeds/action_fields', {
                ...this.action
            })
                .then(response => {
                    this.settings_fields = response.settings_fields;
                })
                .catch((errors) => {
                    this.$handleError(errors);
                })
                .always(() => {
                    this.loading = false;
                });
        },
        emitSave() {
            this.action.is_open = !this.action.is_open;
            this.$emit('update');
        }
    },
    mounted() {
        if (this.action.action_name) {
            this.fetchSettingsFields();
        }
    }
}
</script>
