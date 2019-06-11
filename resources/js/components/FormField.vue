<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">

            <select
                multiple
                :id="field.attribute"
                :disabled="field.disabled">
            </select>

        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

import jQuery from 'jquery'
import Selectize from 'selectize';

window.$ = jQuery;

export default {
    mixins: [FormField, HandlesValidationErrors],

    components: {
        Selectize
    },

    mounted() {
        var $select = $('#' + this.field.attribute);
        $select.selectize({
            options: this.field.options,
            persist: false,
            maxItems: this.field.maxItems,
            searchField: [this.field.optionLabel, this.field.optionValue],
            onInitialize: () => {
                $select.get(0).selectize.setValue(this.value, true);
            },
            onChange: value => {
                this.$emit('input', value);
                this.handleChange(value);
            },
            render: {
                item: (item, escape) => {
                    return '<div class="flex-grow relative rounded-lg cursor-default bg-30 px-3 py-1 mr-1 mb-1">' +
                            '<div>' + escape(item[this.field.optionLabel]) + '</div>' +
                        '<span onClick="$(\'#' + this.field.attribute + '\').get(0).selectize.removeItem(\'' + item[this.field.optionValue] + '\')" class="absolute pin-t pin-r inline-flex ml-2 mt-1 px-1 rounded cursor-pointer font-bold border-transparent hover:bg-50">x</span>' +
                    '</div>';
                },
                option: (item, escape) => {
                    return '<div class="px-2 py-2 ml-lg-n1">' +
                        '<span>' + escape(item[this.field.optionLabel]) + '</span>' +
                    '</div>';
                }
            },
            valueField: this.field.optionValue || 'id',
            labelField: this.field.optionLabel || 'name',
            ...this.settings
        });

        // Extend setActiveOption to add TailWind Classes
        var self = $select.get(0).selectize;
        var original = self.setActiveOption;
        self.setActiveOption = function(option, e) {
            if (self.$activeOption) {
                self.$activeOption.removeClass('bg-50');
            }
            var result = original.apply(self, [option, e]);
            if($(option).hasClass('active')) {
                $(option).addClass('bg-50');
            }
            return result;
        };
    },

    destroyed () {
        if (this.$el.selectize) {
            this.$el.selectize.destroy()
        }
    },

    data() {
        return {
            settings: {
                wrapperClass: 'h-auto min-h-20 form-control',
                inputClass: 'form-input form-input-bordered flex flex-wrap justify-start items-center p-2',
                dropdownClass: '',
                dropdownContentClass: 'w-full h-full form-input-bordered',
            }
        };
    },

    props: ['resourceName', 'resourceId', 'field'],

    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = this.field.value.map((val, index) => {
                if (val.hasOwnProperty(this.field.optionValue) === true) {
                    return val[this.field.optionValue];
                } else {
                    console.error('value(' + index + ') missing key ' + this.field.optionValue)
                }
            }) || []
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            formData.append(this.field.attribute, this.value || '')
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            this.value = value;
        }
    },
}
</script>
