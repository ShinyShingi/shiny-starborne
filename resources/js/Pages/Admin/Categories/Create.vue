<template>
    <Head title="Create Category - Admin" />
    
    <CosmicLayout 
        title="Create Category" 
        background-variant="default"
    >
        <div class="create-category">
            <!-- Header -->
            <div class="category-header">
                <div class="header-info">
                    <h1 class="category-title">
                        Create New 
                        <span class="cosmic-gradient-text">Category</span>
                    </h1>
                    <p class="category-subtitle">
                        Add a new category to organize your cosmic content
                    </p>
                </div>
                <div class="header-actions">
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

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <div class="action-group">
                                <Link 
                                    :href="route('admin.categories.index')"
                                    class="cancel-btn"
                                >
                                    Cancel
                                </Link>
                            </div>
                            <div class="action-group">
                                <Button 
                                    type="submit"
                                    label="Create Category"
                                    icon="pi pi-plus"
                                    class="submit-btn"
                                    :loading="saving"
                                    :disabled="saving || !form.name.trim()"
                                />
                            </div>
                        </div>
                    </form>
                </template>
            </Card>
        </div>
    </CosmicLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import { useToast } from 'primevue/usetoast'
import CosmicLayout from '@/Components/Cosmic/CosmicLayout.vue'
import Card from 'primevue/card'
import InputText from 'primevue/inputtext'
import Textarea from 'primevue/textarea'
import Button from 'primevue/button'

const toast = useToast()

// State
const saving = ref(false)

// Form
const form = useForm({
    name: '',
    description: ''
})

// Computed
const errors = computed(() => form.errors)

// Methods
const submitForm = () => {
    saving.value = true
    
    form.post(route('admin.categories.store'), {
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: 'Category created successfully!',
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
</script>

<style scoped>
.create-category {
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
    transform: translateY(-2px);
}

/* Form */
.form-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 1rem;
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

/* Responsive */
@media (max-width: 768px) {
    .create-category {
        padding: 1rem;
    }

    .category-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
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
}

/* Dark mode adjustments */
:global(.dark) .form-card {
    background: rgba(0, 0, 0, 0.3);
    border-color: rgba(255, 255, 255, 0.1);
}
</style>