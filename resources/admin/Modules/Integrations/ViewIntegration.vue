<template>
    <div class="dashboard fs_box_wrapper">
        <div class="fs_box fs_dashboard_box fs_box_narrow">
            <div class="fs_box_header" style="padding: 20px 15px;font-size: 16px;">
                <el-breadcrumb separator="/">
                    <el-breadcrumb-item :to="{ name: 'integrations' }">API Integrations</el-breadcrumb-item>
                    <template v-if="integration">
                        <el-breadcrumb-item >{{integration.provider}}</el-breadcrumb-item>
                        <el-breadcrumb-item >{{integration.title}}</el-breadcrumb-item>
                    </template>
                    <el-breadcrumb-item v-else>View</el-breadcrumb-item>
                </el-breadcrumb>
            </div>
            <div class="fs_box_body fs_padded_20">
                <div class="fs_integration_form" v-if="!fetching && integration">
                    <div class="text-align-center" v-if="!integration.settings.webhook_verified">
                        <h3>One more step. Please set the webhook now in ThriveCart</h3>
                        <el-input v-model="other_data.webhook_url" :readonly="true" />
                        <p>Please copy the webhook url and set to ThriveCart webhook settings</p>
                        <el-button size="small" @click="fetchIntegration()" type="success">I have done it</el-button>
                        <el-button @click="publish()" size="small" type="danger" v-if="fetch_counter >= 2">Publish Anyway</el-button>
                    </div>
                    <div class="text-align-center" v-else-if="integration.status == 'draft'">
                        <h3>This API integration is on draft mode.</h3>
                        <el-button @click="publish()" type="primary">Publish this API Integration</el-button>
                    </div>
                    <div v-else class="text-align-center">
                        <h3>Your ThriveCart Account ({{integration.remote_id}}) is connected</h3>
                        <template v-if="other_data && other_data.account">
                            <p>Connected Account: {{other_data.account.account_url}}</p>
                            <p>Webhook URL: </p>
                            <el-input style="margin-bottom: 30px;" v-model="other_data.webhook_url" :readonly="true" />
                        </template>
                        <p style="color: red;" v-else>API Validation Failed.</p>
                        <router-link class="el-button el-button--primary btn_link" :to="{ name: 'connectors' }">Setup a new Data Connector</router-link>
                    </div>
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
    name: 'ViewIntegration',
    props: ['id'],
    data() {
        return {
            integration: false,
            html_info: '',
            fetching: false,
            other_data: false,
            fetch_counter: 0
        }
    },
    methods: {
        fetchIntegration() {
            this.fetching = true;
            this.fetch_counter += 1;
            this.$get('integrations/' + this.id)
                .then(response => {
                    this.integration = response.integration;
                    this.html_info = response.html_info;
                    this.other_data = response.other_data;
                })
                .catch(errors => {
                    this.$handleError(errors);
                })
                .always(() => {
                    this.fetching = false;
                });
        },
        publish() {
            this.fetching = true;
            this.$put('integrations/' + this.id, {
                status: 'published'
            })
                .then(response => {
                    this.integration = response.integration;
                    this.html_info = response.html_info;
                    this.other_data = response.other_data;
                })
                .catch(errors => {
                    this.$handleError(errors);
                })
                .always(() => {
                    this.fetching = false;
                });
        }
    },
    mounted() {
        this.fetchIntegration();
    }
}
</script>
