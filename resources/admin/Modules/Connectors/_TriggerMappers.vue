<template>
    <div class="fcon_triggers_wrap">
        <div v-for="(trigger,triggerIndex) in triggers" :key="triggerIndex" class="fc_trigger_map">
            <trigger-map @update="triggerUpdate()" :trigger="trigger" :provider_info="all_triggers[trigger.trigger_provider]" />
        </div>
        <trigger-adder v-if="show_adder" @success="appendTrigger" :integrations="integrations" :all_triggers="all_triggers" />
    </div>
</template>

<script type="text/babel">
import TriggerMap from './_TriggerMap';
import TriggerAdder from './_TriggerAdder';

export default {
    name: 'TriggerMapper',
    props: ['feed_id', 'triggers', 'all_triggers', 'integrations'],
    components: {
        TriggerMap,
        TriggerAdder
    },
    data() {
        return {
            show_adder: false
        }
    },
    methods: {
        appendTrigger(trigger) {
            this.show_adder = false;
            trigger.feed_id = this.feed_id;
            this.triggers.push(trigger);
        },
        triggerUpdate() {
            this.$emit('update');
        }
    },
    mounted() {
        if(!this.triggers.length) {
            this.show_adder = true;
        }
    }
}
</script>
