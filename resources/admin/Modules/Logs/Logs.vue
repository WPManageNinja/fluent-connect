<template>
    <div class="dashboard fs_box_wrapper">
        <div class="fs_box fs_dashboard_box fs_box_narrow">
            <div class="fs_box_header" style="padding: 10px 15px;font-size: 16px;">
                <div class="fs_box_head">
                    <h3>All Trigger & Action Logs</h3>
                </div>
            </div>
            <div v-loading="loading" class="fs_box_body fs_padded_20">
                <el-table stripe border :data="logs">
                    <el-table-column type="expand">
                        <template #default="scope">
                            <div class="fcon_inner_table_wrap">
                                <h3>Actions</h3>
                                <el-table :data="scope.row.action_logs" stripe>
                                    <el-table-column label="Action">
                                        <template #default="scope">
                                            <span v-if="scope.row.reference_url">
                                                <a target="_blank" rel="noopener" :href="scope.row.reference_url">
                                                    {{scope.row.description}}
                                                </a>
                                            </span>
                                            <span v-else>{{scope.row.description}}</span>
                                        </template>
                                    </el-table-column>
                                    <el-table-column :width="100" prop="status" label="Status"></el-table-column>
                                    <el-table-column :width="160" prop="created_at" label="Date Time"></el-table-column>
                                </el-table>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column :width="80" label="ID" prop="id"></el-table-column>
                    <el-table-column label="Trigger">
                        <template #default="scope">
                            {{scope.row.trigger.title}}
                        </template>
                    </el-table-column>
                    <el-table-column :width="150" label="Provider">
                        <template #default="scope">
                            {{scope.row.trigger.trigger_provider}}
                        </template>
                    </el-table-column>
                    <el-table-column :width="100" label="Status" prop="status"></el-table-column>
                    <el-table-column :width="160" label="Date Time" prop="created_at"></el-table-column>
                </el-table>

                <div class="fframe_pagination_wrapper" style="margin-top:20px;text-align:right;">
                    <pagination @fetch="fetch()" :pagination="pagination"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
import Pagination from "../../Pieces/Pagination";

export default {
    name: 'AllLogs',
    components: {
        Pagination
    },
    data() {
        return {
            logs: [],
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
            this.$get('reports/logs', {
                per_page: this.pagination.per_page,
                page: this.pagination.current_page,
                with: ['trigger', 'actions']
            })
                .then(response => {
                    this.logs = response.logs.data;
                    this.pagination.total = response.logs.total;
                })
                .catch((errors) => {
                    this.$handleError(errors);
                })
                .always(() => {
                    this.loading = false;
                });
        }
    },
    mounted() {
        this.fetch();
    }
}
</script>
