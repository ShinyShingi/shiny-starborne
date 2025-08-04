<template>
    <Head :title="`Edit ${category.name} - Admin`" />
    
    <AdminLayout>
        <div class="edit-category">
            <!-- Header -->
            <div class="category-header">
                <div class="header-info">
                    <h1 class="category-title">Edit Category</h1>
                    <p class="category-subtitle">
                        Update category information
                    </p>
                </div>
                <div class="header-actions">
                    <Link 
                        :href="route('admin.categories.show', category.id)"
                        class="view-btn"
                    >
                        View Category
                    </Link>
                    <Link 
                        :href="route('admin.categories.index')"
                        class="back-btn"
                    >
                        <i class="pi pi-arrow-left"></i>
                        Back to Categories
                    </Link>
                </div>
            </div>

            <!-- Category Form -->
            <Card class="form-card">
                <template #content>
                    <form @submit.prevent="submitForm" class="category-form">
                        <div class="form-grid">
                            <!-- Name -->
                            <div class="form-group form-group-full">
                                <label for="name" class="form-label">
                                    <i class="pi pi-tag"></i>
                                    Category Name
                                </label>
                                <InputText
                                    id="name"
                                    v-model="form.name"
                                    placeholder="Enter category name..."
                                    class="form-input"
                                    :class="{ 'p-invalid': errors.name }"
                                />
                                <small v-if="errors.name" class="error-message">
                                    {{ errors.name }}
                                </small>
                                <small class="form-help">
                                    A unique name for your category
                                </small>
                            </div>

                            <!-- Description -->
                            <div class="form-group form-group-full">
                                <label for="description" class="form-label">
                                    <i class="pi pi-align-left"></i>
                                    Description
                                </label>
                                <Textarea
                                    id="description"
                                    v-model="form.description"
                                    placeholder="Describe what this category is about..."
                                    class="form-textarea"
                                    :class="{ 'p-invalid': errors.description }"
                                    rows="4"
                                    auto-resize
                                />
                                <small v-if="errors.description" class="error-message">
                                    {{ errors.description }}
                                </small>
                                <small class="form-help">
                                    Optional description to help users understand this category
                                </small>
                            </div>
                        </div>

                        <!-- Category Meta -->
                        <Card class="meta-card">
                            <template #title>
                                <h3 class="meta-title">
                                    <i class="pi pi-info-circle"></i>
                                    Category Information
                                </h3>
                            </template>
                            <template #content>
                                <div class="meta-grid">
                                    <div class="meta-item">
                                        <span class="meta-label">Created:</span>
                                        <span class="meta-value">{{ formatDate(category.created_at) }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <span class="meta-label">Last Updated:</span>
                                        <span class="meta-value">{{ formatDate(category.updated_at) }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <span class="meta-label">Slug:</span>
                                        <span class="meta-value meta-slug">{{ category.slug }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <span class="meta-label">Posts Count:</span>
                                        <Badge 
                                            :value="category.posts_count || 0" 
                                            :severity="category.posts_count > 0 ? 'info' : 'secondary'"
                                        />
                                    </div>
                                </div>
                            </template>
                        </Card>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <div class="action-group">
                                <Button 
                                    @click="confirmDelete"
                                    label="Delete Category" 
                                    icon="pi pi-trash"
                                    severity="danger"
                                    outlined
                                    class="delete-btn"
                                    :disabled="saving || category.posts_count > 0"
                                />
                            </div>
                            <div class="action-group">
                                <Link 
                                    :href="route('admin.categories.index')"
                                    class="cancel-btn"
                                >
                                    Cancel
                                </Link>
                                <Button 
                                    type="submit"
                                    label="Update Category"
                                    icon="pi pi-save"
                                    class="submit-btn"
                                    :loading="saving"
                                    :disabled="saving || !form.name.trim()"
                                />
                            </div>
                        </div>
                    </form>
                </template>
            </Card>

            <!-- Delete Confirmation Dialog -->
            <Dialog 
                v-model:visible="deleteDialog" 
                header="Confirm Deletion" 
                :modal="true"
                class="cosmic-dialog"
            >
                <div class="delete-confirmation">
                    <i class="pi pi-exclamation-triangle delete-icon"></i>
                    <div class="delete-message">
                        <h3>Delete Category</h3>
                        <p>Are you sure you want to delete "<strong>{{ category.name }}</strong>"?</p>
                        <p v-if="category.posts_count > 0" class="warning-text">
                            This category contains {{ category.posts_count }} post(s). Please reassign or delete the posts first.
                        </p>
                        <p v-else class="warning-text">This action cannot be undone.</p>
                    </div>
                </div>
                <template #footer>
                    <div class="dialog-buttons">
                        <Button 
                            @click="deleteDialog = false"
                            label="Cancel" 
                            icon="pi pi-times"
                            outlined
                            class="cancel-btn"
                        />
                        <Button 
                            v-if="category.posts_count === 0"
                            @click="deleteCategory"
                            label="Delete" 
                            icon="pi pi-trash"
                            severity="danger"
                            class="delete-btn-confirm"
                            :loading="deleting"
                        />
                    </div>
                </template>
            </Dialog>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import { useToast } from 'primevue/usetoast'
import AdminLayout from '@/Components/Admin/AdminLayout.vue'
import Card from 'primevue/card'
import InputText from 'primevue/inputtext'
import Textarea from 'primevue/textarea'
import Button from 'primevue/button'
import Badge from 'primevue/badge'
import Dialog from 'primevue/dialog'

const props = defineProps({
    category: Object
})

const toast = useToast()

// State
const saving = ref(false)
const deleting = ref(false)
const deleteDialog = ref(false)

// Form
const form = useForm({
    name: props.category.name,
    description: props.category.description || ''
})

// Computed
const errors = computed(() => form.errors)

// Methods
const submitForm = () => {
    saving.value = true
    
    form.put(route('admin.categories.update', props.category.id), {
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: 'Category updated successfully!',
                life: 3000
            })
        },
        onError: () => {
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: 'Please check the form for errors',
                life: 3000
            })
        },
        onFinish: () => saving.value = false
    })
}

const confirmDelete = () => {
    deleteDialog.value = true
}

const deleteCategory = () => {
    deleting.value = true
    
    router.delete(route('admin.categories.destroy', props.category.id), {
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: 'Category deleted successfully',
                life: 3000
            })
        },
        onError: () => {
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: 'Failed to delete category',
                life: 3000
            })
        },
        onFinish: () => {
            deleting.value = false
            deleteDialog.value = false
        }
    })
}

const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', { 
        year: 'numeric',
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}
</script>

<style scoped>
.edit-category {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
}

/* Header */
.category-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.category-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: white;
    margin-bottom: 0.5rem;
}

.cosmic-gradient-text {
    background: linear-gradient(135deg, var(--p-primary-color), var(--p-accent-color));
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}

.category-subtitle {
    color: rgba(255, 255, 255, 0.8);
    font-size: 1.1rem;
}

.header-actions {
    display: flex;
    gap: 1rem;
}

.view-btn,
.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    text-decoration: none;
    border-radius: 2rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.view-btn {
    background: linear-gradient(135deg, var(--p-accent-color), #be185d);
    color: white;
}

.back-btn {
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.view-btn:hover,
.back-btn:hover {
    transform: translateY(-2px);
}

/* Form */
.form-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 1rem;
    margin-bottom: 2rem;
}

.meta-card {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 1rem;
    margin-bottom: 2rem;
}

.meta-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: white;
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0;
}

.meta-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.meta-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.meta-label {
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.meta-value {
    color: white;
    font-weight: 500;
}

.meta-slug {
    font-family: monospace;
    background: rgba(255, 255, 255, 0.1);
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.9rem;
}

.category-form {
    padding: 1rem;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
    margin-bottom: 3rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-group-full {
    grid-column: span 1;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: white;
    font-weight: 600;
    font-size: 0.95rem;
}

.form-input,
.form-textarea {
    width: 100%;
}

:deep(.form-input),
:deep(.form-textarea) {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    border-radius: 0.75rem;
    padding: 1rem;
    font-size: 1rem;
    transition: all 0.3s ease;
}

:deep(.form-input:focus),
:deep(.form-textarea:focus) {
    border-color: var(--p-primary-color);
    box-shadow: 0 0 0 2px rgba(147, 51, 234, 0.2);
}

:deep(.form-input::placeholder),
:deep(.form-textarea::placeholder) {
    color: rgba(255, 255, 255, 0.5);
}

:deep(.form-textarea) {
    min-height: 120px;
    resize: vertical;
    line-height: 1.6;
}

.error-message {
    color: #ef4444;
    font-size: 0.85rem;
}

.form-help {
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.85rem;
}

/* Form Actions */
.form-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 2rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.action-group {
    display: flex;
    gap: 1rem;
}

.cancel-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    background: rgba(255, 255, 255, 0.1);
    color: white;
    text-decoration: none;
    border-radius: 2rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
    font-weight: 600;
    transition: all 0.3s ease;
}

.cancel-btn:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}

.submit-btn {
    background: linear-gradient(135deg, var(--p-primary-color), var(--p-primary-600));
    border: none;
    color: white;
    padding: 0.75rem 2rem;
    border-radius: 2rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.submit-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(147, 51, 234, 0.4);
}

.delete-btn {
    padding: 0.75rem 1.5rem;
    border-radius: 2rem;
    font-weight: 600;
}

/* Dialog styles */
.cosmic-dialog :deep(.p-dialog) {
    background: rgba(0, 0, 0, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 1rem;
}

.cosmic-dialog :deep(.p-dialog-header) {
    background: rgba(147, 51, 234, 0.2);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    color: white;
}

.cosmic-dialog :deep(.p-dialog-content) {
    background: transparent;
    color: white;
}

.delete-confirmation {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem 0;
}

.delete-icon {
    font-size: 2rem;
    color: #ef4444;
}

.delete-message h3 {
    color: white;
    margin-bottom: 0.5rem;
}

.delete-message p {
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 0.5rem;
}

.warning-text {
    color: #ef4444;
    font-size: 0.9rem;
}

.dialog-buttons {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

/* Responsive */
@media (max-width: 768px) {
    .edit-category {
        padding: 1rem;
    }

    .category-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }

    .header-actions {
        flex-direction: column;
        width: 100%;
    }

    .category-title {
        font-size: 2rem;
    }

    .form-actions {
        flex-direction: column;
        gap: 1rem;
    }

    .action-group {
        justify-content: center;
        width: 100%;
    }

    .cancel-btn,
    .submit-btn {
        width: 100%;
        justify-content: center;
    }

    .meta-grid {
        grid-template-columns: 1fr;
    }
}

/* Dark mode adjustments */
:global(.dark) .form-card,
:global(.dark) .meta-card {
    background: rgba(0, 0, 0, 0.3);
    border-color: rgba(255, 255, 255, 0.1);
}
</style>