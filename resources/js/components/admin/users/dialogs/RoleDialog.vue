<template>
    <v-dialog v-model="dialogValue" max-width="800" persistent>
        <v-card>
            <v-card-title>
                {{ editingRole ? 'Edit Role' : 'Add New Role' }}
            </v-card-title>
            <v-card-text>
                <v-alert v-if="editingRole && editingRole.is_system" type="info" variant="tonal" class="mb-4">
                    <strong>System Role:</strong> This is a system role. Core properties (name, slug, description,
                    status, order) cannot be modified. Only permissions can be updated using the permissions button.
                </v-alert>
                <v-form ref="roleForm" @submit.prevent="$emit('save')">
                    <v-text-field v-model="form.name" label="Role Name" :rules="[rules.required]" required
                        hint="Display name for the role" persistent-hint class="mb-4" variant="outlined"
                        placeholder="e.g., Administrator" :disabled="editingRole && editingRole.is_system"
                        @blur="autoGenerateSlugFromName"></v-text-field>

                    <v-text-field v-model="form.slug" label="Slug"
                        hint="URL-friendly identifier (auto-generated if empty)" persistent-hint class="mb-4"
                        variant="outlined" placeholder="e.g., admin"
                        :disabled="editingRole && editingRole.is_system"></v-text-field>

                    <v-textarea v-model="form.description" label="Description" hint="Brief description of the role"
                        persistent-hint rows="2" class="mb-4" variant="outlined"
                        placeholder="Short summary of this role"
                        :disabled="editingRole && editingRole.is_system"></v-textarea>

                    <v-text-field v-model.number="form.order" label="Order" type="number"
                        hint="Display order (lower numbers first)" persistent-hint class="mb-4" variant="outlined"
                        placeholder="0" :disabled="editingRole && editingRole.is_system"></v-text-field>

                    <v-switch v-model="form.is_active" label="Active" hint="Inactive roles cannot be assigned to users"
                        persistent-hint class="mb-4" :disabled="editingRole && editingRole.is_system"></v-switch>

                    <!-- Permissions Section -->
                    <v-divider class="my-4"></v-divider>
                    <div class="mb-2">
                        <div class="d-flex justify-space-between align-center mb-2">
                            <div>
                                <h3 class="text-h6 mb-1">Permissions</h3>
                                <p class="text-caption text-grey">Select permissions to assign to this role</p>
                            </div>
                            <div class="d-flex gap-2">
                                <v-btn size="small" variant="outlined" @click="selectAllPermissions" color="primary">
                                    Select All
                                </v-btn>
                                <v-btn size="small" variant="outlined" @click="deselectAllPermissions" color="grey">
                                    Deselect All
                                </v-btn>
                            </div>
                        </div>
                    </div>

                    <!-- Search Filter -->
                    <v-text-field v-model="permissionSearchValue" label="Search permissions"
                        prepend-inner-icon="mdi-magnify" variant="outlined" density="compact" clearable class="mb-4"
                        hint="Search by name, slug, or description" persistent-hint
                        placeholder="Search permissions"></v-text-field>

                    <!-- Permissions List - All Expanded -->
                    <div v-if="Object.keys(filteredGroupedPermissions).length > 0" class="permissions-container mb-4">
                        <div v-for="(permissions, group) in filteredGroupedPermissions" :key="group"
                            class="permission-group mb-4">
                            <v-card variant="outlined">
                                <v-card-title class="d-flex justify-space-between align-center py-2">
                                    <div class="d-flex align-center gap-2">
                                        <span class="text-h6">{{ group.charAt(0).toUpperCase() + group.slice(1)
                                            }}</span>
                                        <v-chip size="small" color="primary" variant="flat">
                                            {{ getSelectedCountInGroup(group) }} / {{ permissions.length }} selected
                                        </v-chip>
                                    </div>
                                    <div class="d-flex gap-1">
                                        <v-btn size="x-small" variant="text" @click="selectAllInGroup(group)"
                                            color="primary">
                                            Select All
                                        </v-btn>
                                        <v-btn size="x-small" variant="text" @click="deselectAllInGroup(group)"
                                            color="grey">
                                            Clear
                                        </v-btn>
                                    </div>
                                </v-card-title>
                                <v-card-text>
                                    <v-row v-if="Array.isArray(permissions)">
                                        <v-col v-for="permission in permissions" :key="permission.id" cols="12" md="6"
                                            lg="4">
                                            <v-checkbox :model-value="isFormPermissionSelected(permission.id)"
                                                @update:model-value="toggleFormPermission(permission.id)"
                                                :label="permission.name" :hint="permission.description" persistent-hint
                                                density="comfortable" color="primary">
                                                <template v-slot:label>
                                                    <div>
                                                        <div class="font-weight-medium">{{ permission.name }}</div>
                                                        <div v-if="permission.description"
                                                            class="text-caption text-grey">
                                                            {{ permission.description }}
                                                        </div>
                                                    </div>
                                                </template>
                                            </v-checkbox>
                                        </v-col>
                                    </v-row>
                                </v-card-text>
                            </v-card>
                        </div>
                    </div>
                    <v-alert v-else type="info" variant="tonal" class="mb-4">
                        <div v-if="permissionSearchValue">
                            No permissions found matching "{{ permissionSearchValue }}"
                        </div>
                        <div v-else>
                            No permissions available. Please ensure permissions are loaded.
                        </div>
                    </v-alert>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn @click="$emit('close')" variant="text">Cancel</v-btn>
                <v-btn v-if="editingRole && editingRole.is_system" @click="$emit('close')" color="primary">
                    Close
                </v-btn>
                <v-btn v-else @click="$emit('save')" color="primary" :loading="saving">
                    {{ editingRole ? 'Update' : 'Create' }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    name: 'RoleDialog',
    props: {
        modelValue: {
            type: Boolean,
            default: false
        },
        editingRole: {
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
        permissionSearch: {
            type: String,
            default: ''
        },
        filteredGroupedPermissions: {
            type: Object,
            default: () => ({})
        },
        getSelectedCountInGroup: {
            type: Function,
            required: true
        },
        isFormPermissionSelected: {
            type: Function,
            required: true
        },
        toggleFormPermission: {
            type: Function,
            required: true
        },
        selectAllPermissions: {
            type: Function,
            required: true
        },
        deselectAllPermissions: {
            type: Function,
            required: true
        },
        selectAllInGroup: {
            type: Function,
            required: true
        },
        deselectAllInGroup: {
            type: Function,
            required: true
        },
        autoGenerateSlugFromName: {
            type: Function,
            required: true
        }
    },
    emits: ['update:modelValue', 'update:permissionSearch', 'save', 'close'],
    computed: {
        dialogValue: {
            get() {
                return this.modelValue;
            },
            set(value) {
                this.$emit('update:modelValue', value);
            }
        },
        permissionSearchValue: {
            get() {
                return this.permissionSearch;
            },
            set(value) {
                this.$emit('update:permissionSearch', value);
            }
        }
    },
    methods: {
        validateForm() {
            return this.$refs.roleForm?.validate?.();
        },
        resetValidation() {
            this.$refs.roleForm?.resetValidation?.();
        }
    }
};
</script>
