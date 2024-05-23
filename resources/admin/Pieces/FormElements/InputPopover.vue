<template>
    <div>
        <el-popover
            ref="inputpopoverref"
            placement="right-end"
            virtual-triggering
            persistent
            :popper-class="'fcrm-smartcodes-popover el-dropdown-list-wrapper ' + popper_extra"
            v-model="visible"
            trigger="click">
            <div class="el_pop_data_group">
                <div class="el_pop_data_headings">
                    <ul>
                        <li
                            v-for="(item,item_index) in data"
                            :data-item_index="item_index"
                            :key="item_index"
                            :class="(activeIndex == item_index) ? 'active_item_selected' : ''"
                            @click="activeIndex = item_index">
                            {{ item.title }}
                        </li>
                    </ul>
                </div>
                <div class="el_pop_data_body">
                    <div v-for="(item,current_index) in data" :key="current_index">
                        <ul v-show="activeIndex == current_index"
                            :class="'el_pop_body_item_'+current_index">
                            <li @click="insertShortcode(code)" v-for="(label,code) in item.shortcodes" :key="code">
                                {{ label }}<span>{{ code }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </el-popover>

        <div v-if="fieldType == 'textarea'" class="input-textarea-value">
            <span class="dashicons fc_clickable dashicons-database" v-popover:input-popover></span>
            <el-input :placeholder="placeholder" :rows="4" type="textarea" v-model="model"></el-input>
        </div>

        <el-input class="fc_pop_append" :placeholder="placeholder" v-else v-model="model" :type="fieldType">
            <template #append>
                <span v-popover:inputpopoverref class="dashicons dashicons-database"></span>
            </template>
        </el-input>
    </div>
</template>

<script type="text/babel">
    export default {
        name: 'inputPopover',
        $emit: ['modelValue'],
        props: {
            modelValue: String,
            placeholder: {
                type: String,
                default: ''
            },
            placement: {
                type: String,
                default: 'bottom'
            },
            fieldType: {
                type: String,
                default: 'text'
            },
            popper_class: {
              type: String,
              default: ''
            },
            data: Array,
            attrName: {
                type: String,
                default: 'attribute_name'
            },
            popper_extra: {
                type: String,
                default: ''
            },
            doc_url: {
                type: String,
                default() {
                    return '';
                }
            }
        },
        data() {
            return {
                model: this.modelValue,
                activeIndex: '0',
                visible: false
            }
        },
        watch: {
            model() {
                this.$emit('modelValue', this.model);
            }
        },
        methods: {
            selectEmoji(imoji) {
                this.insertShortcode(imoji.data);
            },
            insertShortcode(codeString) {
                if (!this.model) {
                    this.model = '';
                }

                this.model = codeString.replace(/param_name/, this.attrName);

                this.visible = false;
            }
        }
    }
</script>
