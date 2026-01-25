<template>
    <div>
        <div class="page-header">
            <h1 class="text-h4 page-title">Role Management</h1>
            <v-btn color="primary" prepend-icon="mdi-plus" @click="openDialog(null)" class="add-button">
                Add New Role
            </v-btn>
        </div>

        <!-- Search and Filter -->
        <v-card class="mb-4">
            <v-card-text>
                <v-row>
                    <v-col cols="12" md="2">
                        <v-select v-model="perPage" :items="perPageOptions" label="Items per page"
                            prepend-inner-icon="mdi-format-list-numbered" variant="outlined" density="compact"
                            @update:model-value="onPerPageChange"></v-select>
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-select v-model="activeFilter" :items="activeOptions" label="Filter by Status"
                            prepend-inner-icon="mdi-filter" variant="outlined" density="compact" clearable
                            @update:model-value="loadRoles"></v-select>
                    </v-col>
                    <v-col cols="12" md="6">
                        <v-text-field v-model="search" label="Search roles" prepend-inner-icon="mdi-magnify"
                            variant="outlined" density="compact" clearable
                            @update:model-value="loadRoles"></v-text-field>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>

        <!-- Roles List -->
        <v-card>
            <v-card-title class="d-flex justify-space-between align-center">
                <span>Roles</span>
                <span class="text-caption text-grey">
                    Total Records: <strong>{{ pagination.total || 0 }}</strong>
                    <span v-if="roles.length > 0">
                        | Showing {{ ((currentPage - 1) * perPage) + 1 }} to {{ Math.min(currentPage * perPage,
                            pagination.total) }} of {{ pagination.total }}
                    </span>
                </span>
            </v-card-title>
            <v-card-text>
                <v-table>
                    <thead>
                        <tr>
                            <th class="sortable" @click="onSort('name')">
                                <div class="d-flex align-center">
                                    Name
                                    <v-icon :icon="getSortIcon('name')" size="small" class="ml-1"></v-icon>
                                </div>
                            </th>
                            <th class="sortable" @click="onSort('slug')">
                                <div class="d-flex align-center">
                                    Slug
                                    <v-icon :icon="getSortIcon('slug')" size="small" class="ml-1"></v-icon>
                                </div>
                            </th>
                            <th>Permissions</th>
                            <th class="sortable" @click="onSort('is_active')">
                                <div class="d-flex align-center">
                                    Status
                                    <v-icon :icon="getSortIcon('is_active')" size="small" class="ml-1"></v-icon>
                                </div>
                            </th>
                            <th>Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="role in roles" :key="role.id">
                            <td>
                                <div class="font-weight-medium">{{ role.name }}</div>
                                <div class="text-caption text-grey">{{ role.description || 'No description' }}</div>
                            </td>
                            <td>
                                <v-chip size="small" variant="outlined">{{ role.slug }}</v-chip>
                            </td>
                            <td>
                                <v-chip size="small" color="primary" variant="text">
                                    {{ role.permissions ? role.permissions.length : 0 }} permissions
                                </v-chip>
                            </td>
                            <td>
                                <v-chip :color="role.is_active ? 'success' : 'error'" size="small">
                                    {{ role.is_active ? 'Active' : 'Inactive' }}
                                </v-chip>
                            </td>
                            <td>
                                <v-chip v-if="role.is_system" color="warning" size="small">
                                    System
                                </v-chip>
                                <span v-else class="text-caption">Custom</span>
                            </td>
                            <td>
                                <v-btn size="small" icon="mdi-pencil" @click="openDialog(role)" variant="text"
                                    :disabled="role.is_system"></v-btn>
                                <v-btn size="small" icon="mdi-shield-key" @click="openPermissionDialog(role)"
                                    variant="text"></v-btn>
                                <v-btn size="small" icon="mdi-delete" @click="deleteRole(role)" variant="text"
                                    color="error" :disabled="role.is_system"></v-btn>
                            </td>
                        </tr>
                        <tr v-if="roles.length === 0">
                            <td colspan="6" class="text-center py-4">No roles found</td>
                        </tr>
                    </tbody>
                </v-table>

                <!-- Pagination and Records Info -->
                <div
                    class="d-flex flex-column flex-md-row justify-space-between align-center align-md-start gap-3 mt-4">
                    <div class="text-caption text-grey">
                        <span v-if="roles.length > 0 && pagination.total > 0">
                            Showing <strong>{{ ((currentPage - 1) * perPage) + 1 }}</strong> to
                            <strong>{{ Math.min(currentPage * perPage, pagination.total) }}</strong> of
                            <strong>{{ pagination.total.toLocaleString() }}</strong> records
                            <span v-if="pagination.last_page > 1" class="ml-2">
                                (Page {{ currentPage }} of {{ pagination.last_page }})
                            </span>
                        </span>
                        <span v-else>
                            No records found
                        </span>
                    </div>
                    <div v-if="pagination.last_page > 1" class="d-flex align-center gap-2">
                        <v-pagination v-model="currentPage" :length="pagination.last_page" :total-visible="7"
                            density="comfortable" @update:model-value="loadRoles">
                        </v-pagination>
                    </div>
                </div>
            </v-card-text>
        </v-card>

        <!-- Role Dialog -->
        <RoleDialog ref="roleDialog" v-model="dialog" :editing-role="editingRole" :saving="saving" :form="form"
            :rules="rules" v-model:permission-search="permissionSearch"
            :filtered-grouped-permissions="filteredGroupedPermissions"
            :get-selected-count-in-group="getSelectedCountInGroup"
            :is-form-permission-selected="isFormPermissionSelected" :toggle-form-permission="toggleFormPermission"
            :select-all-permissions="selectAllPermissions" :deselect-all-permissions="deselectAllPermissions"
            :select-all-in-group="selectAllInGroup" :deselect-all-in-group="deselectAllInGroup"
            :auto-generate-slug-from-name="autoGenerateSlugFromName" @save="saveRole" @close="closeDialog" />

        <!-- Permissions Dialog -->
        <RolePermissionsDialog v-model="permissionDialog" :selected-role="selectedRole"
            :loading-permissions="loadingPermissions" :saving-permissions="savingPermissions"
            :safe-grouped-permissions="safeGroupedPermissions" :is-permission-selected="isPermissionSelected"
            :toggle-permission="togglePermission" @save="savePermissions" @close="closePermissionDialog" />
    </div>
</template>

<script>
/**
 * AdminRoles Component
 * 
 * This component manages roles in the admin panel. It provides functionality to:
 * - View all roles with pagination, search, and filtering
 * - Create new roles with permission assignment
 * - Edit existing roles (except system roles)
 * - Delete custom roles
 * - Assign/update permissions for roles
 * 
 * Features:
 * - Full CRUD operations for roles
 * - Permission management with search and bulk selection
 * - System role protection (cannot edit/delete system roles)
 * - Real-time permission filtering
 */

import commonMixin from '../../../mixins/commonMixin';
import RoleDialog from './dialogs/RoleDialog.vue';
import RolePermissionsDialog from './dialogs/RolePermissionsDialog.vue';

export default {
    components: {
        RoleDialog,
        RolePermissionsDialog
    },
    mixins: [commonMixin],
    data() {
        return {
            // List of all roles fetched from API
            roles: [],

            // Flat array of all permissions (for easy iteration)
            permissions: [],

            // Permissions grouped by their 'group' property (e.g., { 'users': [...], 'posts': [...] })
            groupedPermissions: {},

            // Controls visibility of role create/edit dialog
            dialog: false,

            // Controls visibility of separate permissions management dialog
            permissionDialog: false,

            // Currently editing role object (null when creating new role)
            editingRole: null,

            // Role selected for permission management in separate dialog
            selectedRole: null,

            // Loading state for permissions fetch
            loadingPermissions: false,

            // Loading state for saving permissions
            savingPermissions: false,

            // Array of permission IDs selected in the separate permissions dialog
            selectedPermissions: [],

            // Filter for active/inactive roles (null = all roles)
            activeFilter: null,

            // Options for active status filter dropdown
            activeOptions: [
                { title: 'Active', value: true },
                { title: 'Inactive', value: false }
            ],

            // Form data for role create/edit
            form: {
                name: '',              // Role display name
                slug: '',              // URL-friendly identifier (auto-generated if empty)
                description: '',       // Role description
                is_active: true,       // Whether role is active
                order: 0,              // Display order
                permissions: []        // Array of permission IDs assigned to this role
            },

            // Search query for filtering permissions in the role dialog
            permissionSearch: '',

            // Form validation rules
            rules: {
                required: value => !!value || 'This field is required'
            },

            // Flag to prevent infinite loop when auto-generating slug
            autoGeneratingSlug: false
        };
    },
    computed: {
        /**
         * Safe grouped permissions getter
         * 
         * Ensures groupedPermissions is always a valid object with array values.
         * Filters out any invalid data structures to prevent iteration errors.
         * 
         * @returns {Object} Object with group names as keys and permission arrays as values
         */
        safeGroupedPermissions() {
            // Ensure groupedPermissions is always an object
            if (!this.groupedPermissions || typeof this.groupedPermissions !== 'object') {
                return {};
            }

            // Filter out any non-array values (safety check)
            const safe = {};
            Object.keys(this.groupedPermissions).forEach(key => {
                if (Array.isArray(this.groupedPermissions[key])) {
                    safe[key] = this.groupedPermissions[key];
                }
            });
            return safe;
        },

        /**
         * Filtered grouped permissions based on search query
         * 
         * Filters permissions by name, description, or slug when user types in search box.
         * Returns only groups that have matching permissions.
         * 
         * @returns {Object} Filtered grouped permissions object
         */
        filteredGroupedPermissions() {
            // If no search query, return all permissions
            if (!this.permissionSearch) {
                return this.safeGroupedPermissions;
            }

            // Convert search to lowercase for case-insensitive matching
            const search = this.permissionSearch.toLowerCase();
            const filtered = {};

            // Iterate through each permission group
            Object.keys(this.safeGroupedPermissions).forEach(group => {
                const permissions = this.safeGroupedPermissions[group];
                if (Array.isArray(permissions)) {
                    // Filter permissions that match the search query
                    const filteredPermissions = permissions.filter(permission => {
                        const name = (permission.name || '').toLowerCase();
                        const description = (permission.description || '').toLowerCase();
                        const slug = (permission.slug || '').toLowerCase();
                        // Match if search term appears in name, description, or slug
                        return name.includes(search) || description.includes(search) || slug.includes(search);
                    });

                    // Only include group if it has matching permissions
                    if (filteredPermissions.length > 0) {
                        filtered[group] = filteredPermissions;
                    }
                }
            });

            return filtered;
        }
    },
    /**
     * Component lifecycle hook - runs when component is mounted
     * Loads initial data (roles and permissions)
     */
    async mounted() {
        await this.loadRoles();
        await this.loadPermissions();
    },
    methods: {
        /**
         * Load roles from API with pagination, search, and filtering
         * 
         * Supports:
         * - Pagination (via mixin)
         * - Search by name/slug
         * - Filter by active status
         * 
         * Handles both paginated and non-paginated API responses
         */
        async loadRoles() {
            try {
                this.loading = true;
                const params = this.buildPaginationParams();

                if (this.search) {
                    params.search = this.search;
                }

                if (this.activeFilter !== null) {
                    params.active = this.activeFilter;
                }

                const response = await this.$axios.get('/api/v1/roles', {
                    params,
                    headers: this.getAuthHeaders()
                });

                // Handle both paginated and non-paginated responses
                if (response.data.data) {
                    // Paginated response
                    this.roles = response.data.data || [];
                    this.updatePagination(response.data);
                } else {
                    // Non-paginated response (fallback)
                    this.roles = response.data || [];
                    this.pagination = {
                        current_page: 1,
                        last_page: 1,
                        per_page: this.roles.length,
                        total: this.roles.length
                    };
                }

                // If no roles exist, show a message
                if (this.roles.length === 0) {
                    console.warn('No roles found. Run the seeder to create default roles.');
                }
            } catch (error) {
                if (error.response?.status === 404) {
                    this.handleApiError(error, 'Roles endpoint not found. Please ensure migrations and seeders have run.');
                } else {
                    this.handleApiError(error, 'Failed to load roles');
                }
            } finally {
                this.loading = false;
            }
        },
        /**
         * Load all permissions from API
         * 
         * Uses grouped=true parameter to get ALL permissions without pagination.
         * This is important because we need all permissions available when assigning
         * to roles, not just the first page.
         * 
         * Handles two response formats:
         * 1. Grouped object: { 'group1': [...permissions], 'group2': [...permissions] }
         * 2. Flat array: [...permissions] (will be grouped by 'group' property)
         * 
         * Also creates a flat array of all permissions for easy iteration.
         */
        async loadPermissions() {
            try {
                // Request grouped permissions to get ALL permissions (no pagination)
                // The grouped=true parameter ensures we get all permissions, not just first 10
                const response = await this.$axios.get('/api/v1/permissions', {
                    params: {
                        grouped: true  // This ensures we get all permissions without pagination
                    },
                    headers: this.getAuthHeaders()
                });

                // Handle different response structures
                let permissionsData = response.data;

                // If response has a data property, use it (shouldn't happen with grouped=true, but just in case)
                if (response.data && response.data.data) {
                    permissionsData = response.data.data;
                }

                // Initialize as empty object if not valid
                if (!permissionsData || typeof permissionsData !== 'object') {
                    console.warn('Invalid permissions response structure:', permissionsData);
                    this.groupedPermissions = {};
                    this.permissions = [];
                    return;
                }

                // Check if it's an array (ungrouped format from API)
                if (Array.isArray(permissionsData)) {
                    // Group by group property if available, otherwise use 'general' as default
                    this.groupedPermissions = {};
                    permissionsData.forEach(permission => {
                        // Get group name from permission object, fallback to 'general'
                        const group = permission.group || permission.group_name || 'general';
                        if (!this.groupedPermissions[group]) {
                            this.groupedPermissions[group] = [];
                        }
                        this.groupedPermissions[group].push(permission);
                    });
                } else {
                    // It's already an object (grouped format from API)
                    this.groupedPermissions = permissionsData;
                }

                // Flatten for easier access (create flat array of all permissions)
                // This is useful for operations like "select all permissions"
                this.permissions = [];
                Object.values(this.groupedPermissions).forEach(group => {
                    // Ensure group is an array before spreading (safety check)
                    if (Array.isArray(group)) {
                        this.permissions.push(...group);
                    } else {
                        console.warn('Invalid group structure:', group);
                    }
                });
            } catch (error) {
                console.error('Error loading permissions:', error);
                this.groupedPermissions = {};
                this.permissions = [];
                this.handleApiError(error, 'Failed to load permissions');
            }
        },
        /**
         * Open role create/edit dialog
         * 
         * @param {Object|null} role - Role object to edit, or null to create new role
         * 
         * If editing:
         * - Loads role data into form
         * - Fetches role permissions if not already loaded
         * - Disables certain fields for system roles
         * 
         * If creating:
         * - Resets form to default values
         * - Allows full editing
         */
        async openDialog(role) {
            // Ensure permissions are loaded before opening dialog
            // This is important so all permissions are available for selection
            if (Object.keys(this.groupedPermissions).length === 0) {
                await this.loadPermissions();
            }

            if (role) {
                // Editing existing role
                this.editingRole = role;
                // Load full role data if permissions are not loaded
                let rolePermissions = [];
                if (role.permissions) {
                    // Handle both array of objects and array of IDs
                    rolePermissions = role.permissions.map(p => {
                        if (typeof p === 'object' && p.id) {
                            return p.id;
                        }
                        return p;
                    });
                } else {
                    // Try to fetch role with permissions if not included
                    try {
                        const response = await this.$axios.get(`/api/v1/roles/${role.id}`, {
                            headers: this.getAuthHeaders()
                        });
                        if (response.data && response.data.permissions) {
                            rolePermissions = response.data.permissions.map(p => {
                                if (typeof p === 'object' && p.id) {
                                    return p.id;
                                }
                                return p;
                            });
                        }
                    } catch (error) {
                        console.warn('Could not load role permissions:', error);
                    }
                }

                this.form = {
                    name: role.name,
                    slug: role.slug,
                    description: role.description || '',
                    is_active: role.is_active,
                    order: role.order || 0,
                    permissions: rolePermissions
                };
            } else {
                this.editingRole = null;
                this.form = {
                    name: '',
                    slug: '',
                    description: '',
                    is_active: true,
                    order: 0,
                    permissions: []
                };
            }
            this.dialog = true;
        },
        /**
         * Close role create/edit dialog
         * 
         * Resets all form data, clears search, and resets form validation.
         * Called when user cancels or after successful save.
         */
        closeDialog() {
            this.dialog = false;
            this.editingRole = null;
            this.permissionSearch = '';  // Clear permission search
            this.form = {
                name: '',
                slug: '',
                description: '',
                is_active: true,
                order: 0,
                permissions: []
            };
            // Reset form validation errors
            this.$refs.roleDialog?.resetValidation?.();
        },

        /**
         * Auto-generate slug from role name
         * 
         * Converts role name to URL-friendly slug format.
         * Only runs when creating new role (not editing) and slug is empty.
         * 
         * Example: "Admin User" -> "admin-user"
         */
        autoGenerateSlugFromName() {
            // Auto-generate slug from name if slug is empty and user is not editing slug
            // Only for new roles (not when editing existing role)
            if (!this.form.slug && this.form.name && !this.editingRole) {
                this.form.slug = this.form.name
                    .toLowerCase()                    // Convert to lowercase
                    .replace(/[^a-z0-9]+/g, '-')     // Replace non-alphanumeric with hyphens
                    .replace(/^-+|-+$/g, '');        // Remove leading/trailing hyphens
            }
        },

        /**
         * Save role (create or update)
         * 
         * Validates form, auto-generates slug if needed, and sends data to API.
         * Includes permissions array in the request.
         * 
         * For editing:
         * - Only sends slug if it changed
         * - System roles cannot be edited (handled by disabled fields)
         * 
         * For creating:
         * - Sends all form data including permissions
         * - Backend will generate slug if not provided
         */
        async saveRole() {
            // Validate form first
            if (!this.$refs.roleDialog?.validateForm) {
                this.showError('Form reference not found');
                return;
            }

            if (!this.$refs.roleDialog.validateForm()) {
                return;
            }

            // Auto-generate slug if empty
            if (!this.form.slug && this.form.name) {
                this.form.slug = this.form.name
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            }

            this.saving = true;
            try {
                const url = this.editingRole
                    ? `/api/v1/roles/${this.editingRole.id}`
                    : '/api/v1/roles';

                const method = this.editingRole ? 'put' : 'post';

                // Prepare data - ensure boolean and integer values are properly formatted
                const data = {
                    name: this.form.name.trim(),
                    description: this.form.description ? this.form.description.trim() : null,
                    is_active: this.form.is_active === true || this.form.is_active === 'true' || this.form.is_active === 1,
                    order: parseInt(this.form.order) || 0
                };

                // Handle slug
                if (this.editingRole) {
                    // When editing, only send slug if it changed or is empty
                    if (this.form.slug !== this.editingRole.slug) {
                        if (this.form.slug && this.form.slug.trim()) {
                            data.slug = this.form.slug.trim();
                        } else {
                            // Send empty string to trigger regeneration
                            data.slug = '';
                        }
                    }
                    // If slug didn't change, don't send it (backend keeps existing)
                } else {
                    // When creating, send slug if provided, otherwise omit (backend will generate)
                    if (this.form.slug && this.form.slug.trim()) {
                        data.slug = this.form.slug.trim();
                    }
                }

                // Include permissions in the request
                if (this.form.permissions && Array.isArray(this.form.permissions)) {
                    data.permissions = this.form.permissions;
                }

                await this.$axios[method](url, data, {
                    headers: this.getAuthHeaders()
                });

                this.showSuccess(
                    this.editingRole ? 'Role updated successfully' : 'Role created successfully'
                );
                this.closeDialog();
                await this.loadRoles();
            } catch (error) {
                console.error('Error saving role:', error);
                console.error('Error response:', error.response);
                let message = 'Error saving role';

                if (error.response?.data?.errors) {
                    // Handle validation errors
                    const errors = error.response.data.errors;
                    const errorMessages = [];
                    Object.keys(errors).forEach(key => {
                        if (Array.isArray(errors[key])) {
                            errorMessages.push(`${key}: ${errors[key][0]}`);
                        } else {
                            errorMessages.push(`${key}: ${errors[key]}`);
                        }
                    });
                    message = errorMessages.join('\n');
                } else if (error.response?.data?.message) {
                    message = error.response.data.message;
                }

                this.handleApiError(error, 'Error saving role');
            } finally {
                this.saving = false;
            }
        },
        /**
         * Delete a role
         * 
         * @param {Object} role - Role object to delete
         * 
         * System roles cannot be deleted (protected).
         * Shows confirmation dialog before deletion.
         */
        async deleteRole(role) {
            // Prevent deletion of system roles
            if (role.is_system) {
                this.showError('System roles cannot be deleted');
                return;
            }

            // Confirm deletion with user
            if (!confirm(`Are you sure you want to delete the role "${role.name}"?`)) {
                return;
            }

            try {
                await this.$axios.delete(`/api/v1/roles/${role.id}`, {
                    headers: this.getAuthHeaders()
                });

                this.showSuccess('Role deleted successfully');
                await this.loadRoles();  // Refresh roles list
            } catch (error) {
                this.handleApiError(error, 'Error deleting role');
            }
        },

        /**
         * Open separate permissions management dialog
         * 
         * @param {Object} role - Role to manage permissions for
         * 
         * This opens a dedicated dialog for managing permissions separately
         * from the role create/edit dialog.
         */
        async openPermissionDialog(role) {
            this.selectedRole = role;
            // Extract permission IDs from role object
            // Handle both object format {id: 1, name: '...'} and ID format
            this.selectedPermissions = role.permissions ? role.permissions.map(p => p.id || p) : [];
            this.permissionDialog = true;
        },

        /**
         * Close permissions management dialog
         * 
         * Clears selected role and permissions.
         */
        closePermissionDialog() {
            this.permissionDialog = false;
            this.selectedRole = null;
            this.selectedPermissions = [];
        },

        /**
         * Check if a permission is selected in the separate permissions dialog
         * 
         * @param {number} permissionId - Permission ID to check
         * @returns {boolean} True if permission is selected
         */
        isPermissionSelected(permissionId) {
            return this.selectedPermissions.includes(permissionId);
        },

        /**
         * Toggle permission selection in separate permissions dialog
         * 
         * @param {number} permissionId - Permission ID to toggle
         */
        togglePermission(permissionId) {
            const index = this.selectedPermissions.indexOf(permissionId);
            if (index > -1) {
                // Remove if already selected
                this.selectedPermissions.splice(index, 1);
            } else {
                // Add if not selected
                this.selectedPermissions.push(permissionId);
            }
        },
        // Form permission methods (for role dialog)
        isFormPermissionSelected(permissionId) {
            return this.form.permissions.includes(permissionId);
        },
        toggleFormPermission(permissionId) {
            const index = this.form.permissions.indexOf(permissionId);
            if (index > -1) {
                this.form.permissions.splice(index, 1);
            } else {
                this.form.permissions.push(permissionId);
            }
        },
        getSelectedCountInGroup(group) {
            const permissions = this.filteredGroupedPermissions[group] || this.safeGroupedPermissions[group];
            if (!permissions || !Array.isArray(permissions)) {
                return 0;
            }
            return permissions.filter(p => this.form.permissions.includes(p.id)).length;
        },
        selectAllPermissions() {
            this.permissions.forEach(permission => {
                if (!this.form.permissions.includes(permission.id)) {
                    this.form.permissions.push(permission.id);
                }
            });
        },
        deselectAllPermissions() {
            this.form.permissions = [];
        },
        selectAllInGroup(group) {
            const permissions = this.filteredGroupedPermissions[group] || this.safeGroupedPermissions[group];
            if (permissions && Array.isArray(permissions)) {
                permissions.forEach(permission => {
                    if (!this.form.permissions.includes(permission.id)) {
                        this.form.permissions.push(permission.id);
                    }
                });
            }
        },
        deselectAllInGroup(group) {
            const permissions = this.filteredGroupedPermissions[group] || this.safeGroupedPermissions[group];
            if (permissions && Array.isArray(permissions)) {
                const permissionIds = permissions.map(p => p.id);
                this.form.permissions = this.form.permissions.filter(id => !permissionIds.includes(id));
            }
        },
        /**
         * Save permissions for a role (from separate permissions dialog)
         * 
         * Updates role permissions via API endpoint.
         * Called when user clicks "Save Permissions" in the permissions dialog.
         */
        async savePermissions() {
            this.savingPermissions = true;
            try {
                await this.$axios.put(`/api/v1/roles/${this.selectedRole.id}/permissions`, {
                    permissions: this.selectedPermissions
                }, {
                    headers: this.getAuthHeaders()
                });

                this.showSuccess('Permissions updated successfully');
                this.closePermissionDialog();
                await this.loadRoles();  // Refresh roles list to show updated permissions
            } catch (error) {
                this.handleApiError(error, 'Error saving permissions');
            } finally {
                this.savingPermissions = false;
            }
        },

        /**
         * Handle pagination per page change
         * 
         * Resets to first page and reloads roles with new page size.
         */
        onPerPageChange() {
            this.resetPagination();
            this.loadRoles();
        },

        /**
         * Handle table column sorting
         * 
         * @param {string} field - Field name to sort by
         * 
         * Uses mixin's handleSort method and reloads roles with new sort order.
         */
        onSort(field) {
            this.handleSort(field);
            this.loadRoles();
        }
    }
};
</script>

<style scoped>
/* Permissions Container Styles */
.permissions-container {
    max-height: 600px;
    overflow-y: auto;
    padding-right: 8px;
}

.permission-group {
    margin-bottom: 16px;
}

/* Custom scrollbar for permissions container */
.permissions-container::-webkit-scrollbar {
    width: 8px;
}

.permissions-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.permissions-container::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.permissions-container::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>

<style>
/* Non-scoped styles for add button - ensures it works across all scenarios */
.page-header .add-button {
    visibility: visible !important;
    opacity: 1 !important;
    display: inline-flex !important;
}

.page-header .add-button .v-btn__content {
    visibility: visible !important;
    opacity: 1 !important;
    display: inline-flex !important;
    align-items: center !important;
}

.page-header .add-button .v-btn__prepend {
    visibility: visible !important;
    opacity: 1 !important;
    display: inline-flex !important;
    margin-inline-end: 8px !important;
}

.page-header .add-button .v-icon {
    visibility: visible !important;
    opacity: 1 !important;
    display: inline-flex !important;
    font-size: 20px !important;
}
</style>
