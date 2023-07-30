<template>
    <div v-loading="loading" class="fcon_trigger_view">
        <div @click="trigger.is_open = !trigger.is_open" class="fcon_provider_info">
            <div class="fc_trigger_logo">
                <img v-if="provider_info" :src="provider_info.logo" />
            </div>
            <div class="fc_trigger_titles">
                <span class="fc_trigger_name">{{trigger.title}}</span>
            </div>
            <div class="fc_trigger_actions">
                <el-tag size="mini">{{trigger.status}}</el-tag>
                <span class="fc_item_open"><i class="el-icon el-icon-arrow-down"></i></span>
            </div>
        </div>
        <div class="fcon_trigger_details">
            <div v-if="trigger.is_open" class="fcon_trigger_editor">
                <h3>Select Integration Event</h3>
                <el-select @change="triggerEventChanged()" placeholder="Select Integration Event"
                           v-model="trigger.trigger_name">
                    <el-option
                        v-for="(triggerItem, triggerName) in provider_info.triggers"
                        :key="triggerName"
                        :value="triggerName"
                        :label="triggerItem.title"
                    />
                </el-select>
                <div v-if="trigger.trigger_name">
                    <form-builder :fields="settings_fields" :form-data="trigger.settings" />

                    <div class="el-form-item__content">
                        <label>Internal Label</label>
                        <el-input placeholder="Internal Label" v-model="trigger.title"></el-input>
                    </div>

                    <el-checkbox v-model="trigger.status" true-label="published" false-label="draft">Activate this trigger</el-checkbox>

                    <div style="display: block; margin-top: 10px;">
                        <el-button @click="emitSave()" size="small" type="primary">Save Trigger</el-button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
import FormBuilder from '../../Pieces/FormElements/_FormBuilder';
export default {
    name: 'TriggerMap',
    props: ['trigger', 'provider_info'],
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
            if (!this.trigger.trigger_name) {
                this.trigger.settings = {};
                return false;
            }
            const selectedTrigger = JSON.parse(JSON.stringify(this.provider_info.triggers[this.trigger.trigger_name]));
            this.trigger.settings = selectedTrigger.settings_defaults;
            this.trigger.title = selectedTrigger.title;
            this.fetchSettingsFields();
        },
        fetchSettingsFields() {
            this.loading = true;
            this.$get('feeds/trigger_fields', {
                ...this.trigger
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
            this.trigger.is_open = !this.trigger.is_open;
            this.$emit('update');
        }
    },
    mounted() {
        if(this.trigger.trigger_name) {
            this.fetchSettingsFields();
        }
    }
}
</script>
