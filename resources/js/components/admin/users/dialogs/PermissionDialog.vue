<template>
    <v-dialog v-model="dialogValue" max-width="600" persistent>
        <v-card>
            <v-card-title>
                {{ editingPermission ? 'Edit Permission' : 'Add New Permission' }}
            </v-card-title>
            <v-card-text>
                <v-form ref="permissionForm" @submit.prevent="$emit('save')">
                    <v-text-field v-model="form.name" label="Permission Name" :rules="[rules.required]" required
                        hint="Display name for the permission (e.g., 'Manage Pages')" persistent-hint class="mb-4"
                        variant="outlined" placeholder="e.g., Manage Users"
                        @blur="autoGenerateSlugFromName"></v-text-field>

                    <v-text-field v-model="form.slug" label="Slug"
                        hint="URL-friendly identifier (auto-generated if empty)" persistent-hint
                        class="mb-4" variant="outlined" placeholder="e.g., manage-users"></v-text-field>

                    <v-select v-model="form.group" :items="groups"
                        :item-title="item => typeof item === 'string' ? item : item.name"
                        :item-value="item => typeof item === 'string' ? item : item.name" label="Group"
                        :rules="[rules.required]" required hint="Category/group for organizing permissions"
                        persistent-hint class="mb-4" variant="outlined" placeholder="Select group">
                        <template v-slot:append-item>
                            <v-list-item>
                                <v-text-field v-model="newGroupValue" label="Add New Group" prepend-inner-icon="mdi-plus"
                                    variant="outlined" density="compact" hint="Press Enter to add"
                                    persistent-hint placeholder="New group name"
                                    @keyup.enter="$emit('add-group')"></v-text-field>
                            </v-list-item>
                        </template>
                    </v-select>

                    <v-textarea v-model="form.description" label="Description"
                        hint="Brief description of what this permission allows" persistent-hint rows="2"
                        class="mb-4" variant="outlined" placeholder="Describe the permission"></v-textarea>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn @click="$emit('close')" variant="text">Cancel</v-btn>
                <v-btn @click="$emit('save')" color="primary" :loading="saving">
                    {{ editingPermission ? 'Update' : 'Create' }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    name: 'PermissionDialog',
    props: {
        modelValue: {
            type: Boolean,
            default: false
        },
        editingPermission: {
            type: Object,
            default: null
        },
        saving: {
            type: Boolean,
            default: false
        },
        form: {
            type: Object,
            required: true
        },
        rules: {
            type: Object,
            default: () => ({})
        },
        groups: {
            type: Array,
            default: () => []
        },
        newGroup: {
            type: String,
            default: ''
        },
        autoGenerateSlugFromName: {
            type: Function,
            required: true
        }
    },
    emits: ['update:modelValue', 'update:newGroup', 'save', 'close', 'add-group'],
    computed: {
        dialogValue: {
            get() {
                return this.modelValue;
            },
            set(value) {
                this.$emit('update:modelValue', value);
            }
        },
        newGroupValue: {
            get() {
                return this.newGroup;
            },
            set(value) {
                this.$emit('update:newGroup', value);
            }
        }
    },
    methods: {
        validateForm() {
            return this.$refs.permissionForm?.validate?.();
        },
        resetValidation() {
            this.$refs.permissionForm?.resetValidation?.();
        }
    }
};
</script>
