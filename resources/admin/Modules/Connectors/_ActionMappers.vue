<template>
    <div class="fcon_triggers_wrap">
        <div v-for="(action,actionIndex) in actions" :key="actionIndex" class="fc_trigger_map">
            <action-map @update="triggerUpdate()" :action="action" :provider_info="all_actions[action.action_provider]" />
        </div>
        <action-adder v-if="show_adder" @success="appendAction" :all_actions="all_actions" />
        <el-button style="margin-top: 30px;" size="small" type="info" @click="show_adder = true" v-else>Add Another Action</el-button>
    </div>
</template>

<script type="text/babel">
import ActionMap from './_ActionMap.vue';
import ActionAdder from './_ActionAdder.vue';

export default {
    name: 'ActionMapper',
    props: ['feed_id', 'actions', 'all_actions'],
    components: {
        ActionMap,
        ActionAdder
    },
    data() {
        return {
            show_adder: false
        }
    },
    methods: {
        appendAction(action) {
            this.show_adder = false;
            action.feed_id = this.feed_id;
            this.actions.push(action);
        },
        triggerUpdate() {
            this.$emit('update');
        }
    },
    mounted() {
        if(!this.actions.length) {
            this.show_adder = true;
        }
    }
}
</script>
