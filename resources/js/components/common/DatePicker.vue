<template>
    <v-menu v-model="dateMenu" :close-on-content-click="false" location="bottom" transition="scale-transition">
        <template v-slot:activator="{ props }">
            <v-text-field :model-value="formattedDate" :label="label" readonly v-bind="props" :rules="rules"
                :required="required" :class="fieldClass" prepend-inner-icon="mdi-calendar" :density="density"
                :variant="variant" clearable @click:clear="clearDate"
                @update:model-value="$emit('update:modelValue', $event)"></v-text-field>
        </template>
        <v-date-picker :model-value="datePickerValue" @update:model-value="onDateSelected" color="primary"
            show-adjacent-months hide-header></v-date-picker>
    </v-menu>
</template>

<script>
export default {
    name: 'DatePicker',
    props: {
        modelValue: {
            type: [String, Date],
            default: null
        },
        label: {
            type: String,
            default: 'Date'
        },
        rules: {
            type: Array,
            default: () => []
        },
        required: {
            type: Boolean,
            default: false
        },
        fieldClass: {
            type: String,
            default: ''
        },
        density: {
            type: String,
            default: 'default'
        },
        variant: {
            type: String,
            default: 'outlined'
        }
    },
    emits: ['update:modelValue'],
    data() {
        return {
            dateMenu: false
        };
    },
    computed: {
        formattedDate() {
            if (!this.modelValue) return '';
            try {
                let dateStr;

                // Extract YYYY-MM-DD from various formats (no timezone conversion)
                if (typeof this.modelValue === 'string') {
                    // If it's already YYYY-MM-DD, use it directly
                    if (/^\d{4}-\d{2}-\d{2}$/.test(this.modelValue)) {
                        dateStr = this.modelValue;
                    } else if (this.modelValue.includes('T')) {
                        // Extract date part from ISO string (just take the date part, no timezone conversion)
                        dateStr = this.modelValue.split('T')[0];
                    } else {
                        dateStr = this.modelValue;
                    }
                } else {
                    // Date object - convert to YYYY-MM-DD using local time
                    const year = this.modelValue.getFullYear();
                    const month = String(this.modelValue.getMonth() + 1).padStart(2, '0');
                    const day = String(this.modelValue.getDate()).padStart(2, '0');
                    dateStr = `${year}-${month}-${day}`;
                }

                // Parse YYYY-MM-DD directly
                const [year, month, day] = dateStr.split('-').map(Number);

                if (isNaN(year) || isNaN(month) || isNaN(day)) return this.modelValue;

                // Format as DD/MM/YYYY
                return `${String(day).padStart(2, '0')}/${String(month).padStart(2, '0')}/${year}`;
            } catch (error) {
                return this.modelValue;
            }
        },
        datePickerValue() {
            // Ensure the date picker gets the value in YYYY-MM-DD format
            if (!this.modelValue) return null;
            try {
                let dateStr;

                // Extract YYYY-MM-DD from various formats (no timezone conversion)
                if (typeof this.modelValue === 'string') {
                    // If it's already YYYY-MM-DD, use it directly
                    if (/^\d{4}-\d{2}-\d{2}$/.test(this.modelValue)) {
                        return this.modelValue;
                    } else if (this.modelValue.includes('T')) {
                        // Extract date part from ISO string (just take the date part, no timezone conversion)
                        dateStr = this.modelValue.split('T')[0];
                    } else {
                        dateStr = this.modelValue;
                    }
                } else {
                    // Date object - convert to YYYY-MM-DD using local time
                    const year = this.modelValue.getFullYear();
                    const month = String(this.modelValue.getMonth() + 1).padStart(2, '0');
                    const day = String(this.modelValue.getDate()).padStart(2, '0');
                    dateStr = `${year}-${month}-${day}`;
                }

                // Validate the format
                if (/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) {
                    return dateStr;
                }

                return this.modelValue;
            } catch (error) {
                return this.modelValue;
            }
        }
    },
    methods: {
        onDateSelected(value) {
            // Value from date picker is always in YYYY-MM-DD format
            // Emit it directly as a string (not a Date object) to ensure it stays as YYYY-MM-DD
            if (value && /^\d{4}-\d{2}-\d{2}$/.test(value)) {
                // Emit as string to prevent any Date object conversion
                this.$emit('update:modelValue', String(value));
            } else if (value) {
                // If somehow the format is different, normalize it to YYYY-MM-DD
                try {
                    // Extract date part if it's an ISO string
                    if (typeof value === 'string' && value.includes('T')) {
                        const dateStr = value.split('T')[0];
                        if (/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) {
                            this.$emit('update:modelValue', dateStr);
                        } else {
                            const date = new Date(value);
                            const year = date.getFullYear();
                            const month = String(date.getMonth() + 1).padStart(2, '0');
                            const day = String(date.getDate()).padStart(2, '0');
                            this.$emit('update:modelValue', `${year}-${month}-${day}`);
                        }
                    } else {
                        const date = new Date(value);
                        const year = date.getFullYear();
                        const month = String(date.getMonth() + 1).padStart(2, '0');
                        const day = String(date.getDate()).padStart(2, '0');
                        this.$emit('update:modelValue', `${year}-${month}-${day}`);
                    }
                } catch (error) {
                    this.$emit('update:modelValue', value);
                }
            } else {
                this.$emit('update:modelValue', null);
            }
            this.dateMenu = false;
        },
        clearDate() {
            this.$emit('update:modelValue', null);
        }
    }
};
</script>

<style scoped>
:deep(.v-date-picker-header) {
    display: none !important;
}
</style>
