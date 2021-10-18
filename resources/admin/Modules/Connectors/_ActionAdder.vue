<template>
    <div class="fcon_add_trigger">
        <h3>Select Action</h3>
        <ul class="fcon_provider_selectors">
            <li v-for="(provider,providerKey) in all_actions" :key="providerKey"
                :class="{ fcon_active_trigger : providerKey == action.action_provider }"
                @click="changeActionProvider(providerKey)">
                <img :title="provider.title" :src="provider.logo"/>
            </li>
        </ul>

        <template v-if="this.action.action_provider">
            <el-button style="margin-top: 20px;" @click="actionSuccess()" type="primary">Continue</el-button>
        </template>
    </div>
</template>

<script type="text/babel">
export default {
    name: 'ActionAdder',
    props: ['all_actions'],
    emits: ['success'],
    computed: {
        selectedProvider() {
            const selectedProviderKey = this.action.action_provider;
            if (!selectedProviderKey) {
                return false;
            }

            return this.all_actions[selectedProviderKey];
        }
    },
    data() {
        return {
            action: {
                title: '',
                action_name: '',
                action_provider: '',
                status: 'draft',
                settings: {}
            }
        }
    },
    methods: {
        changeActionProvider(provider) {
            this.action.action_provider = provider;
        },
        actionSuccess() {
            this.action.is_open = true;
            this.$emit('success', this.action);
            this.action = {
                title: '',
                action_name: '',
                action_provider: '',
                status: 'draft',
                settings: {}
            };
        }
    },
    mounted() {
      //  this.changeActionProvider('fluentcrm');
    }
}
</script>
