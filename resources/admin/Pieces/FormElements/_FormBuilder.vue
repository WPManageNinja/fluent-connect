<template>
    <div class="fc_global_form_builder">
        <el-form @submit.prevent.native="nativeSave" :data="formData" :label-position="label_position">
            <with-label v-for="(field,fieldIndex) in fields" :key="fieldIndex" :field="field">
                <component :suggests="suggests" :is="field.type" v-model="formData[fieldIndex]" :field="field"/>
            </with-label>
        </el-form>
    </div>
</template>

<script type="text/babel">
import WithLabel from './_WithLabel';
import InputText from './_InputText';
import WpEditor from '../_wp_editor'
import ImageRadio from './_ImageRadio'
import InputRadio from './_InputRadio'
import InputOptions from './_InputOptions'
import InputGroupedOptions from './_InputGroupedOptions'
import InlineCheckbox from './_InlineCheckbox'
import VerifiedEmailInput from './_VerifiedEmailInput'
import CheckboxGroup from './_InputCheckboxes'
import InputData from './InputPopover.vue'

export default {
    name: 'global_form_builder',
    components: {
        WithLabel,
        InputText,
        WpEditor,
        ImageRadio,
        InputRadio,
        InputOptions,
        InlineCheckbox,
        VerifiedEmailInput,
        CheckboxGroup,
        InputGroupedOptions,
        InputData
    },
    props: {
        formData: {
            type: Object,
            required: false,
            default() {
                return {}
            }
        },
        label_position: {
            required: false,
            type: String,
            default() {
                return 'top';
            }
        },
        fields: {
            required: true,
            type: Object
        },
        suggests: {
            required: false,
            type: Object,
            default() {
                return {}
            }
        }
    },
    methods: {
        nativeSave() {
            this.$emit('nativeSave', this.formData);
        },
        /**
         * Helper function for show/hide dependent elements
         & @return {Boolean}
         */
        compare(operand1, operator, operand2) {
            switch (operator) {
                case '=':
                    return operand1 === operand2
                case '!=':
                    return operand1 !== operand2
            }
        },

        /**
         * Checks if a prop is dependent on another
         * @param listItem
         * @return {boolean}
         */
        dependancyPass(listItem) {
            if (listItem.dependency) {
                const optionPaths = listItem.dependency.depends_on.split('/');

                const dependencyVal = optionPaths.reduce((obj, prop) => {
                    return obj[prop]
                }, this.formData);

                if (this.compare(listItem.dependency.value, listItem.dependency.operator, dependencyVal)) {
                    return true;
                }
                return false;
            }
            return true;
        }
    }
}
</script>
