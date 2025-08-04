<template>
    <Head title="Create Post - Admin" />
    
    <AdminLayout>
        <div class="create-post">
            <!-- Header -->
            <div class="post-header">
                <div class="header-info">
                    <h1 class="post-title">Create Post</h1>
                    <p class="post-subtitle">
                        Create a new blog post
                    </p>
                </div>
                <div class="header-actions">
                    <Link 
                        :href="route('admin.posts.index')"
                        class="back-btn"
                    >
                        ← Back to Posts
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
                                    Leave empty to publish immediately
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
                                <Editor
                                    v-model="form.content"
                                    editor-style="height: 400px"
                                    class="form-editor"
                                    :class="{ 'p-invalid': errors.content }"
                                />
                                <small v-if="errors.content" class="error-message">
                                    {{ errors.content }}
                                </small>
                                <small class="form-help">
                                    Use the rich text editor to format your content
                                </small>
                            </div>
                        </div>

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
                                    :label="form.status === 'published' ? 'Publish Post' : 'Save Post'"
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
        </div>
    </AdminLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { ref, computed, watch } from 'vue'
import { useToast } from 'primevue/usetoast'
import AdminLayout from '@/Components/Admin/AdminLayout.vue'
import ImageUpload from '@/Components/ImageUpload.vue'
import Card from 'primevue/card'
import InputText from 'primevue/inputtext'
import Dropdown from 'primevue/dropdown'
import Calendar from 'primevue/calendar'
import Editor from 'primevue/editor'
import Button from 'primevue/button'
import Badge from 'primevue/badge'
import Dialog from 'primevue/dialog'

const props = defineProps({
    categories: Array
})

const toast = useToast()

// State
const saving = ref(false)
const draftMode = ref(false)
const previewDialog = ref(false)

// Form
const form = useForm({
    title: '',
    content: '',
    image: '',
    category_id: null,
    status: 'draft',
    published_at: null
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
    
    form.post(route('admin.posts.store'), {
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: 'Post created successfully!',
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
    form.status = 'draft'
    form.published_at = null
    
    saving.value = true
    draftMode.value = true
    
    form.post(route('admin.posts.store'), {
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
</script>

<style scoped>
.create-post {
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

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: rgba(255, 255, 255, 0.1);
    color: white;
    text-decoration: none;
    border-radius: 2rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
    font-weight: 600;
    transition: all 0.3s ease;
}

.back-btn:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: var(--p-primary-color);
}

/* Form */
.form-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 1rem;
}

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
.form-textarea,
.form-editor {
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

:deep(.form-editor .p-editor-container) {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 0.75rem;
    transition: all 0.3s ease;
}

:deep(.form-editor .p-editor-toolbar) {
    background: rgba(255, 255, 255, 0.05);
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 0.75rem 0.75rem 0 0;
}

:deep(.form-editor .p-editor-content) {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 0 0 0.75rem 0.75rem;
}

:deep(.form-editor .ql-editor) {
    color: white;
    font-size: 1rem;
    line-height: 1.6;
    min-height: 300px;
}

:deep(.form-editor .p-editor-container.p-focus) {
    border-color: var(--p-primary-color);
    box-shadow: 0 0 0 2px rgba(147, 51, 234, 0.2);
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
.preview-btn {
    padding: 0.75rem 1.5rem;
    border-radius: 2rem;
    font-weight: 600;
}

/* Preview Dialog */
.preview-dialog :deep(.p-dialog) {
    background: rgba(0, 0, 0, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 1rem;
}

.preview-dialog :deep(.p-dialog-header) {
    background: rgba(147, 51, 234, 0.2);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    color: white;
}

.preview-dialog :deep(.p-dialog-content) {
    background: transparent;
    color: white;
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

.preview-body :deep(h1),
.preview-body :deep(h2),
.preview-body :deep(h3),
.preview-body :deep(h4),
.preview-body :deep(h5),
.preview-body :deep(h6) {
    color: white;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.preview-body :deep(p) {
    margin-bottom: 1rem;
}

.preview-body :deep(a) {
    color: var(--p-primary-color);
}

.preview-body :deep(code) {
    background: rgba(255, 255, 255, 0.1);
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-family: monospace;
}

.preview-body :deep(pre) {
    background: rgba(0, 0, 0, 0.5);
    padding: 1rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    margin: 1rem 0;
}

/* Responsive */
@media (max-width: 768px) {
    .create-post {
        padding: 1rem;
    }

    .post-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
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
    }

    .preview-title {
        font-size: 1.5rem;
    }
}

/* Dark mode adjustments */
:global(.dark) .form-card {
    background: rgba(0, 0, 0, 0.3);
    border-color: rgba(255, 255, 255, 0.1);
}
</style>