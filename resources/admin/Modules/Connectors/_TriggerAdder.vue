<template>
    <div class="fcon_add_trigger">
        <h3>Select Integration</h3>
        <ul class="fcon_provider_selectors">
            <li v-for="(provider,providerKey) in all_triggers"
                :class="{ fcon_active_trigger : providerKey == trigger.trigger_provider }"
                @click="changeTriggerProvider(providerKey)">
                <img :title="provider.title" :src="provider.logo"/>
            </li>
        </ul>
        <template v-if="selectedProvider && selectedProvider.require_integration_selector">
            <h4>Select Integration Account</h4>
            <el-select v-model="trigger.integration_id" placeholder="Select Integration Account">
                <el-option v-for="integration in selectedRemoteIntegrations" :key="integration.id"
                           :value="integration.id" :label="integration.title"></el-option>
            </el-select>
            <el-button style="margin-top: 20px;" :disabled="!trigger.integration_id" @click="triggerSuccess()" type="primary">Continue</el-button>
        </template>

        <template v-else-if="this.trigger.trigger_provider">
            <el-button style="margin-top: 20px;" @click="triggerSuccess()" type="primary">Continue</el-button>
        </template>
    </div>
</template>

<script type="text/babel">
export default {
    name: 'TriggerAdder',
    props: ['all_triggers', 'integrations'],
    emits: ['success'],
    computed: {
        selectedProvider() {
            const selectedProviderKey = this.trigger.trigger_provider;
            if (!selectedProviderKey) {
                return false;
            }

            return this.all_triggers[selectedProviderKey];
        },
        selectedRemoteIntegrations() {
            if (!this.selectedProvider) {
                return [];
            }

            return this.integrations.filter((integration) => {
                return integration.provider == this.trigger.trigger_provider;
            });
        }
    },
    data() {
        return {
            trigger: {
                title: '',
                integration_id: '',
                trigger_name: '',
                trigger_provider: '',
                status: 'draft',
                settings: {}
            }
        }
    },
    methods: {
        changeTriggerProvider(provider) {
            this.trigger.trigger_provider = provider;
            this.$nextTick(() => {
                if (this.selectedRemoteIntegrations.length > 0) {
                    this.trigger.integration_id = this.selectedRemoteIntegrations[0].id;
                }
            });
        },
        triggerSuccess() {
            this.trigger.is_open = true;
            this.$emit('success', this.trigger);
            this.trigger = {
                title: '',
                integration_id: '',
                trigger_name: '',
                trigger_provider: '',
                status: 'draft',
                settings: {}
            };
        }
    },
    mounted() {
        this.changeTriggerProvider('thrivecart');
    }
}
</script>
