<template>
    <div class="dashboard fs_box_wrapper">
        <div class="fs_box fs_dashboard_box fs_box_narrow">
            <div class="fs_box_header" style="padding: 10px 15px;font-size: 16px;">
                <div class="fs_box_head">
                    <h3>All Data Connect Feeds</h3>
                </div>
                <div class="fs_box_actions">
                    <el-button type="primary" @click="adding_new = true">Add New Connector</el-button>
                </div>
            </div>
            <div class="fs_box_body fs_padded_20">
                <el-table v-if="all_loaded" :data="feeds" border stripe>
                    <el-table-column prop="id" label="ID" width="90"/>
                    <el-table-column label="Title">
                        <template #default="scope">
                            <router-link :to="{ name: 'edit_connector', params: { feed_id: scope.row.id } }">
                                {{scope.row.title}}
                            </router-link>
                        </template>
                    </el-table-column>
                    <el-table-column prop="status" label="Status" width="120"/>
                    <el-table-column label="Actions" width="120">
                        <template #default="scope">
                            <router-link :to="{ name: 'edit_connector', params: { feed_id: scope.row.id } }">
                                <i class="el-icon-edit"></i>
                            </router-link>
                            <el-popconfirm
                                confirm-button-text="Yes, Delete this"
                                cancel-button-text="No"
                                icon="el-icon-info"
                                icon-color="red"
                                title="Are you sure to delete this? All associate data connectors will be deleted too"
                                @confirm="deleteConnector(scope.row.id)"
                            >
                                <template #reference>
                                    <el-button style="margin-left: 10px; color: red;" type="text" size="mini"
                                               icon="el-icon-delete"></el-button>
                                </template>
                            </el-popconfirm>
                        </template>
                    </el-table-column>
                </el-table>

                <div class="fframe_pagination_wrapper" style="margin-top:20px;text-align:right;">
                    <pagination @fetch="fetch()" :pagination="pagination"/>
                </div>

                <div v-if="loading">
                    <el-skeleton :rows="3" animated/>
                </div>
            </div>
        </div>

        <el-dialog
            v-model="adding_new"
            :append-to-body="true"
            title="Add a New Data Connect Feed"
            width="60%"
        >

            <el-form label-position="top" :data="new_feed">
                <el-form-item label="Data Connector Title">
                    <el-input type="text" placeholder="Connector Title" v-model="new_feed.title"/>
                </el-form-item>
            </el-form>

            <template #footer>
              <span class="dialog-footer">
                <el-button @click="adding_new = false">Cancel</el-button>
                <el-button type="primary" @click="addFeed()">Continue</el-button>
                </span>
            </template>
        </el-dialog>
    </div>
</template>

<script type="text/babel">
import Pagination from "@/admin/Pieces/Pagination";

export default {
    name: 'DataConnectorFeeds',
    components: {
        Pagination
    },
    data() {
        return {
            loading: false,
            pagination: {
                current_page: 1,
                per_page: 15,
                total: 0
            },
            feeds: [],
            adding_new: false,
            new_feed: {
                title: ''
            },
            saving: true,
            all_loaded: false
        }
    },
    methods: {
        fetch() {
            this.loading = true;
            this.$get('feeds', {
                per_page: this.pagination.per_page,
                page: this.pagination.current_page
            })
                .then(response => {
                    this.feeds = response.feeds.data;
                    this.pagination.total = response.feeds.total;
                })
                .catch((errors) => {
                    this.$handleError(errors);
                })
                .always(() => {
                    this.loading = false;
                    this.all_loaded = true;
                });
        },
        addFeed() {
            this.saving = true;
            this.$post('feeds', {
                ...this.new_feed
            })
                .then(response => {
                    this.$notify.success(response.message);
                    this.$router.push({
                        name: 'edit_connector',
                        params: {
                            feed_id: response.feed.id
                        }
                    })
                })
                .catch((errors) => {
                    this.$handleError(errors);
                })
                .always(() => {
                    this.saving = false;
                });
        },
        deleteConnector(id) {
            this.$del('feeds/' + id)
                .then(response => {
                    this.$notify.success(response.message);
                    this.fetch();
                })
                .catch((errors) => {
                    this.$handleError(errors);
                })
                .always(() => {

                });
        }
    },
    mounted() {
        this.fetch();
    }
}
</script>
