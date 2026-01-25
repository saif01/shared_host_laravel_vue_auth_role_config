<template>
    <v-dialog v-model="dialogValue" max-width="900" scrollable persistent>
        <v-card>
            <v-card-title>
                {{ editingUser ? 'Edit User' : 'Add New User' }}
            </v-card-title>
            <v-card-text class="pa-0">
                <v-form ref="form" @submit.prevent="$emit('save')">
                    <v-tabs v-model="activeTabValue" bg-color="grey-lighten-4">
                        <v-tab value="basic">Basic Information</v-tab>
                        <v-tab value="profile">Profile Information</v-tab>
                        <v-tab value="security">Security</v-tab>
                    </v-tabs>

                    <v-window v-model="activeTabValue">
                        <!-- Basic Information Tab -->
                        <v-window-item value="basic">
                            <div class="pa-6">
                                <v-text-field v-model="form.name" label="Full Name" :rules="[rules.required]"
                                    required class="mb-4" variant="outlined" hint="Enter the user's full name"
                                    persistent-hint placeholder="e.g., Jane Doe"></v-text-field>

                                <v-text-field v-model="form.email" label="Email" type="email"
                                    :rules="[rules.required, rules.email]" required class="mb-4"
                                    variant="outlined" hint="Use a valid email address" persistent-hint
                                    placeholder="name@example.com"></v-text-field>

                                <v-select v-model="form.role_ids" :items="roles" item-title="label"
                                    item-value="value" label="Roles" :rules="[rules.required]" required multiple
                                    chips class="mb-4" variant="outlined" hint="Select one or more roles"
                                    persistent-hint placeholder="Select roles">
                                    <template v-slot:item="{ props, item }">
                                        <v-list-item v-bind="props">
                                            <template v-slot:title>
                                                {{ item.raw.label }}
                                                <v-chip v-if="item.raw.is_system" size="x-small" color="warning"
                                                    class="ml-2">
                                                    System
                                                </v-chip>
                                            </template>
                                            <template v-slot:subtitle>
                                                {{ item.raw.description }}
                                            </template>
                                        </v-list-item>
                                    </template>
                                    <template v-slot:selection="{ item, index }">
                                        <v-chip v-if="index < 2" size="small" class="mr-1">
                                            {{ item.raw.label }}
                                        </v-chip>
                                        <span v-if="index === 2" class="text-grey text-caption align-self-center">
                                            (+{{ form.role_ids.length - 2 }} others)
                                        </span>
                                    </template>
                                </v-select>

                                <!-- Avatar Upload Section -->
                                <div class="mb-4">
                                    <div class="text-subtitle-2 font-weight-medium mb-2">Avatar</div>

                                    <!-- Avatar Preview -->
                                    <div v-if="form.avatar" class="mb-3 text-center">
                                        <v-avatar size="80" class="mb-2">
                                            <v-img :src="form.avatar ? resolveImageUrl(form.avatar) : ''"
                                                alt="Avatar Preview"></v-img>
                                        </v-avatar>
                                        <div>
                                            <v-btn size="small" variant="text" color="error"
                                                prepend-icon="mdi-delete" @click="$emit('clear-avatar')">Remove Avatar</v-btn>
                                        </div>
                                    </div>

                                    <!-- File Upload -->
                                    <v-file-input :model-value="avatarFileValue" label="Upload Avatar" variant="outlined"
                                        density="comfortable" color="primary" accept="image/*"
                                        prepend-icon="mdi-image"
                                        hint="Upload an avatar image (JPG, PNG, GIF, WebP - Max 5MB). Recommended size: 200x200px"
                                        persistent-hint show-size @update:model-value="onAvatarFileUpdate"
                                        class="mb-3" placeholder="Choose an image">
                                        <template v-slot:append-inner v-if="uploadingAvatar">
                                            <v-progress-circular indeterminate size="20"
                                                color="primary"></v-progress-circular>
                                        </template>
                                    </v-file-input>
                                </div>
                            </div>
                        </v-window-item>

                        <!-- Profile Information Tab -->
                        <v-window-item value="profile">
                            <div class="pa-6">
                                <v-row>
                                    <v-col cols="12" md="6">
                                        <v-text-field v-model="form.phone" label="Phone Number" variant="outlined"
                                            prepend-inner-icon="mdi-phone" hint="e.g., +8801707080401"
                                            persistent-hint placeholder="+1 555 123 4567"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="6">
                                    <DatePickerVuetifyInput fieldLabel="Date of Birth"
                                        :initial-date="form.date_of_birth || todayDate"
                                        variant="outlined" hint="YYYY-MM-DD" :persistent-hint="true"
                                        @trigerInputValue="form.date_of_birth = $event" />
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-select v-model="form.gender" :items="genderOptions" label="Gender"
                                            variant="outlined" prepend-inner-icon="mdi-gender-male-female"
                                            clearable hint="Optional" persistent-hint placeholder="Select gender"></v-select>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-textarea v-model="form.address" label="Address" variant="outlined"
                                            rows="2" prepend-inner-icon="mdi-map-marker" hint="Street address"
                                            persistent-hint placeholder="Street address"></v-textarea>
                                    </v-col>
                                    <v-col cols="12" md="4">
                                        <v-text-field v-model="form.city" label="City" variant="outlined"
                                            prepend-inner-icon="mdi-city" hint="City or town" persistent-hint
                                            placeholder="City"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="4">
                                        <v-text-field v-model="form.state" label="State/Province" variant="outlined"
                                            prepend-inner-icon="mdi-map" hint="State or province" persistent-hint
                                            placeholder="State/Province"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="4">
                                        <v-text-field v-model="form.country" label="Country" variant="outlined"
                                            prepend-inner-icon="mdi-flag" hint="Country name" persistent-hint
                                            placeholder="Country"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="4">
                                        <v-text-field v-model="form.postal_code" label="Postal Code" variant="outlined"
                                            prepend-inner-icon="mdi-mailbox" hint="ZIP or postal code"
                                            persistent-hint placeholder="ZIP / Postal code"></v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-textarea v-model="form.bio" label="Bio" variant="outlined" rows="4"
                                            prepend-inner-icon="mdi-text"
                                            hint="Brief description about the user" persistent-hint
                                            :counter="1000" placeholder="Short bio about the user"></v-textarea>
                                    </v-col>
                                </v-row>
                            </div>
                        </v-window-item>

                        <!-- Security Tab -->
                        <v-window-item value="security">
                            <div class="pa-6">
                                <v-text-field v-model="form.password" label="Password"
                                    :type="showPassword ? 'text' : 'password'"
                                    :rules="editingUser ? [] : [rules.required, rules.minLength]"
                                    :required="!editingUser" hint="Leave blank to keep current password"
                                    persistent-hint class="mb-4" prepend-inner-icon="mdi-lock" variant="outlined"
                                    placeholder="Enter password">
                                    <template v-slot:append-inner>
                                        <v-btn icon variant="text" size="small" @click="toggleShowPassword">
                                            <v-icon>{{ showPassword ? 'mdi-eye-off' : 'mdi-eye' }}</v-icon>
                                        </v-btn>
                                    </template>
                                </v-text-field>

                                <v-text-field v-if="!editingUser || form.password"
                                    v-model="form.password_confirmation" label="Confirm Password"
                                    :type="showPasswordConfirmation ? 'text' : 'password'" :rules="form.password ? [
                                        () => !!form.password_confirmation || 'Please confirm your password',
                                        () => form.password_confirmation === form.password || 'Passwords do not match'
                                    ] : []" :required="!!form.password"
                                    prepend-inner-icon="mdi-lock-check" variant="outlined"
                                    hint="Re-enter the password" persistent-hint placeholder="Re-enter password"></v-text-field>
                            </div>
                        </v-window-item>
                    </v-window>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn @click="$emit('close')" variant="text">Cancel</v-btn>
                <v-btn @click="$emit('save')" color="primary" :loading="saving">
                    {{ editingUser ? 'Update' : 'Create' }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import DatePickerVuetifyInput from '../../../common/DatePickerVuetifyInput.vue';

export default {
    name: 'UserDialog',
    components: {
        DatePickerVuetifyInput
    },
    computed: {
        todayDate() {
            return new Date().toISOString().slice(0, 10);
        }
    },
    props: {
        modelValue: {
            type: Boolean,
            default: false
        },
        editingUser: {
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
        roles: {
            type: Array,
            default: () => []
        },
        rules: {
            type: Object,
            default: () => ({})
        },
        activeTab: {
            type: String,
            default: 'basic'
        },
        genderOptions: {
            type: Array,
            default: () => []
        },
        showPassword: {
            type: Boolean,
            default: false
        },
        showPasswordConfirmation: {
            type: Boolean,
            default: false
        },
        avatarFile: {
            type: [Array, Object],
            default: null
        },
        uploadingAvatar: {
            type: Boolean,
            default: false
        },
        resolveImageUrl: {
            type: Function,
            required: true
        }
    },
    emits: [
        'update:modelValue',
        'update:activeTab',
        'update:showPassword',
        'update:showPasswordConfirmation',
        'update:avatarFile',
        'save',
        'close',
        'clear-avatar',
        'avatar-upload'
    ],
    computed: {
        dialogValue: {
            get() {
                return this.modelValue;
            },
            set(value) {
                this.$emit('update:modelValue', value);
            }
        },
        activeTabValue: {
            get() {
                return this.activeTab;
            },
            set(value) {
                this.$emit('update:activeTab', value);
            }
        },
        avatarFileValue: {
            get() {
                return this.avatarFile;
            },
            set(value) {
                this.$emit('update:avatarFile', value);
            }
        }
    },
    methods: {
        validateForm() {
            return this.$refs.form?.validate?.();
        },
        resetValidation() {
            this.$refs.form?.resetValidation?.();
        },
        toggleShowPassword() {
            this.$emit('update:showPassword', !this.showPassword);
        },
        onAvatarFileUpdate(value) {
            this.$emit('update:avatarFile', value);
            this.$nextTick(() => {
                this.$emit('avatar-upload');
            });
        }
    }
};
</script>
