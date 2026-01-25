<template>
    <v-dialog v-model="dialogValue" max-width="800" persistent>
        <v-card>
            <v-card-title>
                Manage Permissions - {{ selectedRole?.name }}
                <v-chip v-if="selectedRole?.is_system" color="warning" size="small" class="ml-2">
                    System Role
                </v-chip>
            </v-card-title>
            <v-card-text>
                <div v-if="loadingPermissions" class="text-center py-4">
                    <v-progress-circular indeterminate color="primary"></v-progress-circular>
                </div>
                <div v-else>
                    <div v-if="Object.keys(safeGroupedPermissions).length === 0" class="text-center py-4">
                        <p class="text-grey">No permissions available</p>
                    </div>
                    <div v-else>
                        <div v-for="(permissions, group) in safeGroupedPermissions" :key="group" class="mb-6">
                            <h3 class="text-h6 mb-3">{{ group.charAt(0).toUpperCase() + group.slice(1) }}</h3>
                            <v-row v-if="Array.isArray(permissions)">
                                <v-col v-for="permission in permissions" :key="permission.id" cols="12" md="6">
                                    <v-checkbox :model-value="isPermissionSelected(permission.id)"
                                        @update:model-value="togglePermission(permission.id)"
                                        :label="permission.name" :hint="permission.description" persistent-hint
                                        density="compact"></v-checkbox>
                                </v-col>
                            </v-row>
                            <v-alert v-else type="warning" variant="tonal" class="mt-2">
                                Invalid permission group structure
                            </v-alert>
                        </div>
                    </div>
                </div>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn @click="$emit('close')" variant="text">Cancel</v-btn>
                <v-btn @click="$emit('save')" color="primary" :loading="savingPermissions">
                    Save Permissions
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    name: 'RolePermissionsDialog',
    props: {
        modelValue: {
            type: Boolean,
            default: false
        },
        selectedRole: {
            type: Object,
            default: null
        },
        loadingPermissions: {
            type: Boolean,
            default: false
        },
        savingPermissions: {
            type: Boolean,
            default: false
        },
        safeGroupedPermissions: {
            type: Object,
            default: () => ({})
        },
        isPermissionSelected: {
            type: Function,
            required: true
        },
        togglePermission: {
            type: Function,
            required: true
        }
    },
    emits: ['update:modelValue', 'close', 'save'],
    computed: {
        dialogValue: {
            get() {
                return this.modelValue;
            },
            set(value) {
                this.$emit('update:modelValue', value);
            }
        }
    }
};
</script>
