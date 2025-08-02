<template>
    <Head :title="`Edit ${post.title} - Admin`" />
    
    <CosmicLayout 
        title="Edit Post" 
        background-variant="default"
    >
        <div class="edit-post">
            <!-- Header -->
            <div class="post-header">
                <div class="header-info">
                    <h1 class="post-title">
                        Edit 
                        <span class="cosmic-gradient-text">Stellar Article</span>
                    </h1>
                    <p class="post-subtitle">
                        Refine your cosmic masterpiece
                    </p>
                </div>
                <div class="header-actions">
                    <Link 
                        :href="route('blog.show', post.slug)"
                        class="view-btn"
                        target="_blank"
                    >
                        <i class="pi pi-external-link"></i>
                        View Post
                    </Link>
                    <Link 
                        :href="route('admin.posts.index')"
                        class="back-btn"
                    >
                        <i class="pi pi-arrow-left"></i>
                        Back to Posts
                    </Link>
                </div>
            </div>

            <!-- Post Form -->
            <Card class="form-card">
                <template #content>
                    <form @submit.prevent="submitForm" class="post-form">
                        <div class="form-grid">
                            <!-- Title -->
                            <div class="form-group form-group-full">
                                <label for="title" class="form-label">
                                    <i class="pi pi-file-edit"></i>
                                    Post Title
                                </label>
                                <InputText
                                    id="title"
                                    v-model="form.title"
                                    placeholder="Enter a compelling title..."
                                    class="form-input"
                                    :class="{ 'p-invalid': errors.title }"
                                />
                                <small v-if="errors.title" class="error-message">
                                    {{ errors.title }}
                                </small>
                            </div>

                            <!-- Category -->
                            <div class="form-group">
                                <label for="category" class="form-label">
                                    <i class="pi pi-tag"></i>
                                    Category
                                </label>
                                <Dropdown
                                    id="category"
                                    v-model="form.category_id"
                                    :options="categories"
                                    option-label="name"
                                    option-value="id"
                                    placeholder="Select a category"
                                    class="form-dropdown"
                                    :class="{ 'p-invalid': errors.category_id }"
                                />
                                <small v-if="errors.category_id" class="error-message">
                                    {{ errors.category_id }}
                                </small>
                            </div>

                            <!-- Status -->
                            <div class="form-group">
                                <label for="status" class="form-label">
                                    <i class="pi pi-circle"></i>
                                    Status
                                </label>
                                <Dropdown
                                    id="status"
                                    v-model="form.status"
                                    :options="statusOptions"
                                    option-label="label"
                                    option-value="value"
                                    placeholder="Select status"
                                    class="form-dropdown"
                                    :class="{ 'p-invalid': errors.status }"
                                />
                                <small v-if="errors.status" class="error-message">
                                    {{ errors.status }}
                                </small>
                            </div>

                            <!-- Published Date -->
                            <div class="form-group" v-if="form.status === 'published'">
                                <label for="published_at" class="form-label">
                                    <i class="pi pi-calendar"></i>
                                    Publish Date
                                </label>
                                <Calendar
                                    id="published_at"
                                    v-model="form.published_at"
                                    placeholder="Select publish date"
                                    class="form-calendar"
                                    :show-time="true"
                                    :show-seconds="false"
                                    date-format="yy-mm-dd"
                                />
                                <small class="form-help">
                                    Leave empty to use current date/time
                                </small>
                            </div>

                            <!-- Featured Image -->
                            <div class="form-group form-group-full">
                                <ImageUpload
                                    v-model="form.image"
                                    label="Featured Image"
                                    help-text="Upload a featured image for this post (optional)"
                                    :image-alt="form.title || 'Post image'"
                                />
                                <small v-if="errors.image" class="error-message">
                                    {{ errors.image }}
                                </small>
                            </div>

                            <!-- Content -->
                            <div class="form-group form-group-full">
                                <label for="content" class="form-label">
                                    <i class="pi pi-align-left"></i>
                                    Content
                                </label>
                                <Textarea
                                    id="content"
                                    v-model="form.content"
                                    placeholder="Write your stellar content here..."
                                    class="form-textarea"
                                    :class="{ 'p-invalid': errors.content }"
                                    rows="15"
                                    auto-resize
                                />
                                <small v-if="errors.content" class="error-message">
                                    {{ errors.content }}
                                </small>
                                <small class="form-help">
                                    Supports HTML and Markdown
                                </small>
                            </div>
                        </div>

                        <!-- Post Meta -->
                        <Card class="meta-card">
                            <template #title>
                                <h3 class="meta-title">
                                    <i class="pi pi-info-circle"></i>
                                    Post Information
                                </h3>
                            </template>
                            <template #content>
                                <div class="meta-grid">
                                    <div class="meta-item">
                                        <span class="meta-label">Author:</span>
                                        <span class="meta-value">{{ post.user.name }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <span class="meta-label">Created:</span>
                                        <span class="meta-value">{{ formatDate(post.created_at) }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <span class="meta-label">Last Updated:</span>
                                        <span class="meta-value">{{ formatDate(post.updated_at) }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <span class="meta-label">Slug:</span>
                                        <span class="meta-value meta-slug">{{ post.slug }}</span>
                                    </div>
                                </div>
                            </template>
                        </Card>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <div class="action-group">
                                <Button 
                                    @click="saveDraft"
                                    label="Save as Draft" 
                                    icon="pi pi-save"
                                    severity="secondary"
                                    outlined
                                    class="draft-btn"
                                    :loading="saving && draftMode"
                                    :disabled="saving"
                                />
                                <Button 
                                    @click="confirmDelete"
                                    label="Delete Post" 
                                    icon="pi pi-trash"
                                    severity="danger"
                                    outlined
                                    class="delete-btn"
                                    :disabled="saving"
                                />
                            </div>
                            <div class="action-group">
                                <Button 
                                    @click="previewPost"
                                    label="Preview" 
                                    icon="pi pi-eye"
                                    severity="info"
                                    outlined
                                    class="preview-btn"
                                    :disabled="!form.title || !form.content"
                                />
                                <Button 
                                    type="submit"
                                    :label="form.status === 'published' ? 'Update & Publish' : 'Update Post'"
                                    :icon="form.status === 'published' ? 'pi pi-send' : 'pi pi-save'"
                                    class="submit-btn"
                                    :loading="saving && !draftMode"
                                    :disabled="saving"
                                />
                            </div>
                        </div>
                    </form>
                </template>
            </Card>

            <!-- Preview Dialog -->
            <Dialog 
                v-model:visible="previewDialog" 
                header="Post Preview" 
                :modal="true"
                class="preview-dialog"
                :style="{ width: '90vw', maxWidth: '1000px' }"
            >
                <div class="preview-content">
                    <div class="preview-header">
                        <h1 class="preview-title">{{ form.title || 'Untitled Post' }}</h1>
                        <div class="preview-meta">
                            <Badge 
                                :value="form.status || 'draft'" 
                                :severity="form.status === 'published' ? 'success' : 'warning'"
                            />
                            <span class="preview-category">
                                {{ selectedCategory?.name || 'No Category' }}
                            </span>
                        </div>
                    </div>
                    <div class="preview-body" v-html="form.content || 'No content'"></div>
                </div>
                <template #footer>
                    <Button 
                        @click="previewDialog = false"
                        label="Close" 
                        icon="pi pi-times"
                        outlined
                    />
                </template>
            </Dialog>

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
                        <h3>Delete Post</h3>
                        <p>Are you sure you want to delete "<strong>{{ post.title }}</strong>"?</p>
                        <p class="warning-text">This action cannot be undone.</p>
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
                            @click="deletePost"
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
    </CosmicLayout>
</template>

<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import { ref, computed, watch } from 'vue'
import { useToast } from 'primevue/usetoast'
import CosmicLayout from '@/Components/Cosmic/CosmicLayout.vue'
import ImageUpload from '@/Components/ImageUpload.vue'
import Card from 'primevue/card'
import InputText from 'primevue/inputtext'
import Dropdown from 'primevue/dropdown'
import Calendar from 'primevue/calendar'
import Textarea from 'primevue/textarea'
import Button from 'primevue/button'
import Badge from 'primevue/badge'
import Dialog from 'primevue/dialog'

const props = defineProps({
    post: Object,
    categories: Array
})

const toast = useToast()

// State
const saving = ref(false)
const deleting = ref(false)
const draftMode = ref(false)
const previewDialog = ref(false)
const deleteDialog = ref(false)

// Form
const form = useForm({
    title: props.post.title,
    content: props.post.content,
    image: props.post.image || '',
    category_id: props.post.category_id,
    status: props.post.status,
    published_at: props.post.published_at ? new Date(props.post.published_at) : null
})

// Options
const statusOptions = [
    { label: 'Draft', value: 'draft' },
    { label: 'Published', value: 'published' }
]

// Computed
const selectedCategory = computed(() => {
    return props.categories.find(cat => cat.id === form.category_id)
})

const errors = computed(() => form.errors)

// Watch status change to clear published_at when switching to draft
watch(() => form.status, (newStatus) => {
    if (newStatus === 'draft') {
        form.published_at = null
    }
})

// Methods
const submitForm = () => {
    saving.value = true
    draftMode.value = false
    
    form.put(route('admin.posts.update', props.post.id), {
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: 'Post updated successfully!',
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

const saveDraft = () => {
    const originalStatus = form.status
    const originalPublishedAt = form.published_at
    
    form.status = 'draft'
    form.published_at = null
    
    saving.value = true
    draftMode.value = true
    
    form.put(route('admin.posts.update', props.post.id), {
        onSuccess: () => {
            toast.add({
                severity: 'info',
                summary: 'Draft Saved',
                detail: 'Post saved as draft',
                life: 3000
            })
        },
        onError: () => {
            form.status = originalStatus
            form.published_at = originalPublishedAt
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: 'Failed to save draft',
                life: 3000
            })
        },
        onFinish: () => {
            saving.value = false
            draftMode.value = false
        }
    })
}

const previewPost = () => {
    previewDialog.value = true
}

const confirmDelete = () => {
    deleteDialog.value = true
}

const deletePost = () => {
    deleting.value = true
    
    router.delete(route('admin.posts.destroy', props.post.id), {
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: 'Post deleted successfully',
                life: 3000
            })
        },
        onError: () => {
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: 'Failed to delete post',
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
.edit-post {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

/* Header */
.post-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.post-title {
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

.post-subtitle {
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

/* Form - Same as Create but with Meta Card */
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

/* Form styles - inherit from Create component */
.post-form {
    padding: 1rem;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 3rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-group-full {
    grid-column: span 2;
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
.form-dropdown,
.form-calendar,
.form-textarea {
    width: 100%;
}

:deep(.form-input),
:deep(.form-dropdown .p-dropdown),
:deep(.form-calendar .p-inputtext),
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
:deep(.form-dropdown .p-dropdown:focus),
:deep(.form-calendar .p-inputtext:focus),
:deep(.form-textarea:focus) {
    border-color: var(--p-primary-color);
    box-shadow: 0 0 0 2px rgba(147, 51, 234, 0.2);
}

:deep(.form-input::placeholder),
:deep(.form-textarea::placeholder) {
    color: rgba(255, 255, 255, 0.5);
}

:deep(.form-textarea) {
    min-height: 200px;
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

.submit-btn {
    background: linear-gradient(135deg, var(--p-primary-color), var(--p-primary-600));
    border: none;
    color: white;
    padding: 0.75rem 2rem;
    border-radius: 2rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(147, 51, 234, 0.4);
}

.draft-btn,
.preview-btn,
.delete-btn {
    padding: 0.75rem 1.5rem;
    border-radius: 2rem;
    font-weight: 600;
}

/* Dialog styles - inherit from Create and Index components */
.preview-dialog :deep(.p-dialog),
.cosmic-dialog :deep(.p-dialog) {
    background: rgba(0, 0, 0, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 1rem;
}

.preview-dialog :deep(.p-dialog-header),
.cosmic-dialog :deep(.p-dialog-header) {
    background: rgba(147, 51, 234, 0.2);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    color: white;
}

.preview-dialog :deep(.p-dialog-content),
.cosmic-dialog :deep(.p-dialog-content) {
    background: transparent;
    color: white;
}

.preview-content {
    max-height: 70vh;
    overflow-y: auto;
}

.preview-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.preview-title {
    font-size: 2rem;
    font-weight: 700;
    color: white;
    margin-bottom: 1rem;
}

.preview-meta {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.preview-category {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.9rem;
}

.preview-body {
    color: rgba(255, 255, 255, 0.9);
    line-height: 1.7;
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
    .edit-post {
        padding: 1rem;
    }

    .post-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }

    .header-actions {
        flex-direction: column;
        width: 100%;
    }

    .post-title {
        font-size: 2rem;
    }

    .form-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .form-group-full {
        grid-column: span 1;
    }

    .form-actions {
        flex-direction: column;
        gap: 1rem;
    }

    .action-group {
        justify-content: center;
        flex-wrap: wrap;
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