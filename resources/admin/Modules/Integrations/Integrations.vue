<template>
    <div class="dashboard fs_box_wrapper">
        <div class="fs_box fs_dashboard_box fs_box_narrow">
            <div class="fs_box_header" style="padding: 10px 15px;font-size: 16px;">
                <div class="fs_box_head">
                    <h3>API Integrations</h3>
                </div>
                <div class="fs_box_actions">
                    <router-link :to="{name: 'new_integration', query: { integration_key: 'thrivecart' }}"
                                 class="el-button el-button--primary el-button--small btn_link">Connect New API
                    </router-link>
                </div>
            </div>
            <div v-loading="loading" class="fs_box_body fs_padded_20">
                <el-table stripe border :data="integrations">
                    <el-table-column :width="80" label="ID" prop="id"></el-table-column>
                    <el-table-column label="Title" prop="title"></el-table-column>
                    <el-table-column :width="150" label="Provider" prop="provider"></el-table-column>
                    <el-table-column :width="100" label="Status" prop="status"></el-table-column>
                    <el-table-column label="Actions" width="180">
                        <template #default="scope">
                            <router-link :to="{ name: 'view_integration', params: { id: scope.row.id } }">
                                <i class="el-icon-view"></i>
                            </router-link>
                            <el-popconfirm
                                confirm-button-text="Yes, Delete this"
                                cancel-button-text="No"
                                icon="el-icon-info"
                                icon-color="red"
                                title="Are you sure to delete this? All associate data connectors will be deleted too"
                                @confirm="deleteIntegration(scope.row.id)"
                            >
                                <template #reference>
                                    <el-button style="margin-left: 10px; color: red;" type="text" size="mini"
                                               icon="el-icon-delete"></el-button>
                                </template>
                            </el-popconfirm>
                        </template>
                    </el-table-column>
                </el-table>

                <div class="fframe_pagination_wrapper">
                    <pagination @fetch="fetch()" :pagination="pagination"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
import Pagination from "../../Pieces/Pagination";

export default {
    name: 'AllIntegrations',
    components: {
        Pagination
    },
    data() {
        return {
            integrations: [],
            loading: false,
            pagination: {
                current_page: 1,
                per_page: 10,
                total: 0
            }
        }
    },
    methods: {
        fetch() {
            this.loading = true;
            this.$get('integrations', {
                per_page: this.per_page,
                page: this.current_page
            })
                .then(response => {
                    this.integrations = response.integrations.data;
                    this.pagination.total = response.integrations.total;
                })
                .catch((errors) => {
                    this.$handleError(errors);
                })
                .always(() => {
                    this.loading = false;
                });
        },
        deleteIntegration(id) {
            this.$del('integrations/' + id)
                .then(response => {
                    this.$notify.success(response.message);
                    this.fetch()
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
