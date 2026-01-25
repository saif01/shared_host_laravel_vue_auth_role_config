<template>
    <div>
        <!-- dbFormatDate: {{ dbFormatDate }} -->
        <v-menu v-model="isMenuOpen" :close-on-content-click="false">
            <template v-slot:activator="{ props }">
                <v-text-field :label="dateFieldLavel" :placeholder="fieldPlaceHolder" :model-value="computedCustomValue"
                    readonly v-bind="props" clearable @click:clear="onClear()" density="compact"
                    :hint="hint" :persistent-hint="persistentHint"
                    :class="required ? 'required' : null" :variant="variant ? variant : 'underlined'"
                    :rules="mergedRules">
                </v-text-field>
            </template>
            <v-date-picker show-adjacent-months hide-header hide-actions position="sticky" v-model="selectedDate"
                @update:modelValue="isMenuOpen = false, initialDate = null"
                :min="min ? yesterdayIso : null"></v-date-picker>
        </v-menu>
    </div>
</template>

<script>
export default {

    props: ["fieldLabel", "required", "variant", "min", "initialDate", "hint", "persistentHint", "rules"],

    data() {
        return {
            isMenuOpen: null,
            selectedDate: (this.initialDate == null) ? null : new Date(this.initialDate),
            dbFormatDate: null,
            dateFieldLavel: this.fieldLabel ?? "Selected date",
            fieldPlaceHolder: "Enter " + this.fieldLabel ?? "Date",
            yesterdayIso: new Date(new Date().setDate(new Date().getDate() - 1)),
        }
    },

    computed: {
        computedCustomValue() {
            if (this.selectedDate) {
                return this.frmtInsideDate(this.selectedDate)
            }
        },
        mergedRules() {
            const baseRules = Array.isArray(this.rules) ? this.rules : [];
            const requiredRule = this.required ? (v => !!v || `This ${this.dateFieldLavel} is required`) : null;
            return [...baseRules, requiredRule].filter(Boolean);
        }
    },

    watch: {
        computedCustomValue: function (val) {
            // Only emit if dbFormatDate has been computed
            if (val && this.dbFormatDate !== null) {
                this.$emit("trigerInputValue", this.dbFormatDate);
            }
        },
        initialDate: function (newVal) {
            if (newVal) {
                this.selectedDate = new Date(newVal);
                // Force computation and emission after selectedDate is updated
                this.$nextTick(() => {
                    // Access computedCustomValue to trigger frmtInsideDate and set dbFormatDate
                    if (this.computedCustomValue && this.dbFormatDate) {
                        this.$emit("trigerInputValue", this.dbFormatDate);
                    }
                });
            } else {
                this.selectedDate = null;
                this.dbFormatDate = null;
                this.$emit("trigerInputValue", null);
            }
        },
    },

    mounted() {
        // Emit initial date value if provided on component mount
        if (this.initialDate && this.selectedDate) {
            this.$nextTick(() => {
                // Access computedCustomValue to trigger frmtInsideDate and set dbFormatDate
                if (this.computedCustomValue && this.dbFormatDate) {
                    this.$emit("trigerInputValue", this.dbFormatDate);
                }
            });
        }
    },



    methods: {

        // onClear
        onClear() {
            this.selectedDate = null;
            this.dbFormatDate = null;
            this.$emit("trigerInputValue", null);
        },

        // frmtInsideDate
        frmtInsideDate(date) {
            // Convert the input string to a Date object
            const inputDate = new Date(date)

            // Extract day, month, and year components
            const dday = inputDate.getDate()
            const mmonth = inputDate.getMonth() + 1 // Note: Months are zero-indexed, so we add 1
            const YYYY = inputDate.getFullYear()

            const DD = dday.toString().padStart(2, '0')
            const MM = mmonth.toString().padStart(2, '0')

            // DD/MM/YYYY
            const formattedDate = `${DD}/${MM}/${YYYY}`

            // YYYY-MM-DD
            this.dbFormatDate = `${YYYY}-${MM}-${DD}`

            //console.log(formattedDate) // Output: "28/12/2023"
            return formattedDate
        },
    },

    // created(){
    //     console.log('initialDate ', this.initialDate)
    // }
}
</script>
