<template>
    <div class="dashboard fs_box_wrapper">
        <div class="fs_box fs_dashboard_box fs_box_narrow">
            <div class="fs_box_header" style="padding: 20px 15px;font-size: 16px;">
                <el-breadcrumb separator="/">
                    <el-breadcrumb-item :to="{ name: 'integrations' }">API Integrations</el-breadcrumb-item>
                    <el-breadcrumb-item>New Integration</el-breadcrumb-item>
                </el-breadcrumb>
            </div>
            <div class="fs_box_body fs_padded_20">
                <div class="fs_integration_form" v-if="!loading">
                    <el-form label-position="top" :data="settings">
                        <el-form-item label="ThriveCart API Key">
                            <el-input placeholder="ThriveCart API Key" type="password" v-model="settings.api_key"/>
                            <p>You can find the ThriveCart API Key from Account > Settings > API & Webhooks</p>
                        </el-form-item>
                        <div class="text-align-right">
                            <el-button v-loading="validating" @click="validateKey()" type="primary">Next</el-button>
                        </div>
                    </el-form>
                </div>
                <div v-else>
                    <el-skeleton :rows="3" animated/>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
export default {
    name: 'NewIntegrations',
    data() {
        return {
            integration_providers: {},
            loading: false,
            fetching: false,
            integration_key: this.$route.query.integration_key,
            integration_info: {},
            settings: {},
            settings_fields: {},
            validating: false
        }
    },
    methods: {
        getIntegrations() {
            this.loading = true;
            this.$get('integrations/providers')
                .then(response => {
                    this.integration_providers = response.providers;
                })
                .catch(errors => {
                    this.$handleErrors(errors);
                })
                .always(() => {
                    this.loading = false;
                });
        },
        fetchIntegration() {
            this.fetching = true;
            this.$get('integrations/providers/' + this.integration_key)
                .then(response => {
                    this.settings = response.settings;
                    this.settings_fields = response.settings_fields;
                    this.integration_info = response.info;
                })
                .catch(errors => {
                    this.$handleError(errors);
                })
                .always(() => {
                    this.fetching = false;
                });
        },
        validateKey() {
            this.validating = true;
            this.$post('integrations/providers/thrivecart', {
                validate: true,
                api_key: this.settings.api_key
            })
                .then(response => {
                    this.$notify.success(response.message);
                    this.$router.push({ name: 'view_integration', params: { id: response.integration.id } });
                })
                .catch(errors => {
                    this.$handleError(errors);
                })
                .always(() => {
                    this.validating = false;
                });
        }
    },
    mounted() {
        // this.getIntegrations();
        // if (this.integration_key) {
        //     this.fetchIntegration();
        // }
    }
}
</script>
