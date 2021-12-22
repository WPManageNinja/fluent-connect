<template>
    <div class="dashboard fs_box_wrapper">
        <div class="fs_box fs_dashboard_box fs_box_narrow">
            <div class="fs_box_header" style="padding: 20px 15px;font-size: 16px;">
                Good {{ greetingTime }} {{ me.full_name }}!
            </div>
            <div class="fs_box_body fs_padded_20">
                <div v-if="loading">
                    <el-skeleton :rows="3" animated/>
                </div>
                <template v-if="require_setup">
                    <div class="fcon_welcome text-align-center">
                        <h2>Thanks for installing <b>Fluent Connect</b></h2>
                        <p>Using Fluent Connect, you can easily integrate External Services or Migrate From other CRMs
                            into FluentCRM</p>
                        <hr/>
                    </div>
                    <div class="text-align-center" v-if="!has_fluentcrm">
                        <h4>To getting started please install and activate FluentCRM</h4>
                        <el-button @click="installFluentCRM()" type="primary">Install and Activate FluentCRM</el-button>
                    </div>
                    <div class="text-align-center" v-else-if="!has_thrivecart">
                        <h4>Fluent Connect will let you connect your ThriveCart with FluentCRM.</h4>
                        <router-link :to="{name: 'new_integration', query: { integration_key: 'thrivecart' }}"
                                     class="el-button el-button--primary btn_link">Connect with ThriveCart
                        </router-link>
                    </div>
                </template>
                <div v-else-if="!loading">
                    <h2>Awesome! Here is your quick overview</h2>
                    <div class="fcon_box_widgets">
                        <div @click="$router.push({ name: 'connectors' })" class="fcon_box_widget">
                            <h3>{{stats.runners_count}}</h3>
                            <h4>Jobs Completed</h4>
                        </div>
                        <div @click="$router.push({ name: 'logs' })" class="fcon_box_widget">
                            <h3>{{stats.actions_count}}</h3>
                            <h4>Tasks Completed</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
export default {
    name: 'Dashboard',
    data() {
        return {
            me: this.appVars.me,
            loading: false,
            has_fluentcrm: false,
            has_thrivecart: false,
            require_setup: false,
            installing: false,
            stats: false
        }
    },
    computed: {
        greetingTime() {
            const m = this.moment();
            let g = null; //return g

            if (!m || !m.isValid()) {
                return;
            } //if we can't find a valid or filled moment, we return.

            const split_afternoon = 12 //24hr time to split the afternoon
            const split_evening = 17 //24hr time to split the evening
            const currentHour = parseFloat(m.format("HH"));

            if (currentHour >= split_afternoon && currentHour <= split_evening) {
                g = "afternoon";
            } else if (currentHour >= split_evening) {
                g = "evening";
            } else {
                g = "morning";
            }

            return g;
        }
    },
    methods: {
        getDashboardInfo() {
            this.loading = true;
            this.$get('reports/dashboard')
                .then(response => {
                    this.has_thrivecart = response.has_thrivecart;
                    this.has_fluentcrm = response.has_fluentcrm;
                    this.require_setup = response.require_setup;
                    this.stats = response.stats;
                })
                .catch((errors) => {
                    this.$handleError(errors);
                })
                .always(() => {
                    this.loading = false;
                });
        },
        installFluentCRM() {
            this.installing = true;
            this.$post('reports/install-fluentcrm')
                .then(response => {
                    this.$notify.success(response.message);
                    this.getDashboardInfo();
                })
                .catch((errors) => {
                    this.$handleError(errors);
                })
                .always(() => {
                    this.installing = false;
                });
        }
    },
    mounted() {
        this.getDashboardInfo();
    }
};
</script>
