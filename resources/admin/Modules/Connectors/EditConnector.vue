<template>
    <div class="dashboard fs_box_wrapper">
        <div class="fs_box fs_dashboard_box fs_box_narrow">
            <div class="fs_box_header" style="padding: 10px 15px;font-size: 16px;">
                <div class="fs_box_head">
                    <el-breadcrumb separator="/">
                        <el-breadcrumb-item :to="{ name: 'connectors' }">Data Connectors</el-breadcrumb-item>
                        <el-breadcrumb-item>Edit</el-breadcrumb-item>
                        <template v-if="feed">
                            <el-breadcrumb-item>{{ feed.title }} ({{feed.status}})</el-breadcrumb-item>
                        </template>
                    </el-breadcrumb>
                </div>
                <div v-if="feed" class="fs_box_actions">
                    <span style="margin-right: 10px; " class="fcon_status">Status: {{feed.status}}
                        <el-switch @change="updateFeed()" active-value="published" inactive-value="draft" v-model="feed.status" />
                    </span>
                    <el-button size="small" type="primary" @click="updateFeed()">Update Feed</el-button>
                </div>
            </div>
            <div v-if="loading" class="fs_box_body fs_padded_20">
                <div>
                    <el-skeleton :rows="3" animated/>
                </div>
            </div>
            <div v-else-if="feed">
                <div style="padding: 20px 0px;">
                    <el-input type="text" placeholder="Connector Title" v-model="feed.title"/>
                </div>
                <div class="fs_box fs_dashboard_box">
                    <div class="fs_box_header" style="padding: 10px 15px;font-size: 16px;">
                        <div class="fs_box_head">
                            <h3>Set Your Trigger</h3>
                        </div>
                    </div>
                    <div class="fs_box_body fs_padded_20">
                        <trigger-mappers @update="updateFeed()" :integrations="integrations" :feed_id="feed.id" :all_triggers="all_triggers" :triggers="feed.triggers" />
                    </div>
                </div>
                <div style="margin-top: 30px;" class="fs_box fs_dashboard_box">
                    <div class="fs_box_header" style="padding: 10px 15px;font-size: 16px;">
                        <div class="fs_box_head">
                            <h3>Then do these tasks</h3>
                        </div>
                    </div>
                    <div class="fs_box_body fs_padded_20">
                        <div v-if="!feed.triggers.length" class="text-align-center">
                            <h3>Please setup a trigger first</h3>
                        </div>
                        <template v-else>
                            <action-mappers @update="updateFeed()" :actions="feed.actions" :all_actions="all_actions" :feed_id="feed.id" />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
import TriggerMappers from './_TriggerMappers';
import ActionMappers from './_ActionMappers';

export default {
    name: 'DataConnectorFeedEditor',
    props: ['feed_id'],
    components: {
        TriggerMappers,
        ActionMappers
    },
    data() {
        return {
            loading: true,
            all_actions: {},
            all_triggers: {},
            integrations: [],
            feed: false,
            saving: true
        }
    },
    methods: {
        fetch() {
            this.loading = true;
            this.$get('feeds/' + this.feed_id, {
                with: ['all_actions', 'all_triggers', 'integrations']
            })
                .then(response => {
                    this.feed = response.feed;
                    this.all_actions = response.all_actions;
                    this.all_triggers = response.all_triggers;
                    this.integrations = response.integrations;
                })
                .catch((errors) => {
                    this.$handleError(errors);
                })
                .always(() => {
                    this.loading = false;
                });
        },
        updateFeed() {
            this.saving = true;
            this.$put('feeds/' + this.feed_id, {
                feed: JSON.stringify(this.feed)
            })
                .then(response => {
                    this.$notify.success(response.message);
                    this.feed = response.feed;
                })
                .catch((errors) => {
                    this.$handleError(errors);
                })
                .always(() => {
                    this.saving = false;
                });
        }
    },
    mounted() {
        this.fetch();
    }
}
</script>
