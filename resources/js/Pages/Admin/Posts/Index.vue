<template>
    <Head title="Posts - Admin" />
    
    <AdminLayout>
        <div class="posts-index">
            <!-- Header -->
            <div class="posts-header">
                <div class="header-info">
                    <h1 class="posts-title">Posts</h1>
                    <p class="posts-subtitle">
                        Manage your blog posts
                    </p>
                </div>
                <div class="header-actions">
                    <Link 
                        :href="route('admin.posts.create')"
                        class="create-btn"
                    >
                        + New Post
                    </Link>
                </div>
            </div>

            <!-- Filters -->
            <Card class="filters-card">
                <template #content>
                    <div class="filters">
                        <div class="search-group">
                            <span class="p-input-icon-left">
                                <i class="pi pi-search"></i>
                                <InputText
                                    v-model="searchQuery"
                                    placeholder="Search posts..."
                                    class="search-input"
                                    @input="onSearch"
                                />
                            </span>
                            <Button 
                                v-if="searchQuery"
                                @click="clearSearch"
                                icon="pi pi-times"
                                text
                                rounded
                                severity="secondary"
                                class="clear-btn"
                            />
                        </div>
                    </div>
                </template>
            </Card>

            <!-- Posts Table -->
            <Card class="table-card">
                <template #content>
                    <DataTable
                        :value="posts.data"
                        :paginator="true"
                        :rows="posts.per_page"
                        :total-records="posts.total"
                        :lazy="true"
                        @page="onPage"
                        @sort="onSort"
                        sort-mode="single"
                        class="cosmic-datatable"
                        data-key="id"
                        responsive-layout="scroll"
                        :loading="loading"
                    >
                        <template #empty>
                            <div class="empty-state">
                                <i class="pi pi-file empty-icon"></i>
                                <h3>No Posts Found</h3>
                                <p>Create your first post to get started</p>
                                <Link 
                                    :href="route('admin.posts.create')"
                                    class="empty-action-btn"
                                >
                                    <i class="pi pi-plus"></i>
                                    Create Post
                                </Link>
                            </div>
                        </template>

                        <Column field="title" header="Title" sortable class="name-column">
                            <template #body="{ data }">
                                <div class="post-name">
                                    <strong>{{ data.title }}</strong>
                                    <small class="post-slug">{{ data.slug }}</small>
                                </div>
                            </template>
                        </Column>

                        <Column field="status" header="Status" sortable class="status-column">
                            <template #body="{ data }">
                                <Badge 
                                    :value="data.status" 
                                    :severity="data.status === 'published' ? 'success' : 'warning'"
                                />
                            </template>
                        </Column>

                        <Column field="category.name" header="Category" sortable class="category-column">
                            <template #body="{ data }">
                                <div class="post-category">
                                    {{ data.category?.name || 'No category' }}
                                </div>
                            </template>
                        </Column>

                        <Column field="user.name" header="Author" sortable class="author-column">
                            <template #body="{ data }">
                                <div class="post-author">
                                    {{ data.user.name }}
                                </div>
                            </template>
                        </Column>

                        <Column field="created_at" header="Created" sortable class="date-column">
                            <template #body="{ data }">
                                <div class="date-info">
                                    {{ formatDate(data.created_at) }}
                                </div>
                            </template>
                        </Column>

                        <Column header="Actions" class="actions-column">
                            <template #body="{ data }">
                                <div class="action-buttons">
                                    <Link 
                                        :href="route('admin.posts.show', data.id)"
                                        class="action-btn view-btn"
                                        v-tooltip="'View Post'"
                                    >
                                        <i class="pi pi-eye"></i>
                                    </Link>
                                    <Link 
                                        :href="route('admin.posts.edit', data.id)"
                                        class="action-btn edit-btn"
                                        v-tooltip="'Edit Post'"
                                    >
                                        <i class="pi pi-pencil"></i>
                                    </Link>
                                    <Button 
                                        @click="confirmDelete(data)"
                                        icon="pi pi-trash"
                                        severity="danger"
                                        text
                                        rounded
                                        class="action-btn delete-btn"
                                        v-tooltip="'Delete Post'"
                                    />
                                </div>
                            </template>
                        </Column>
                    </DataTable>
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
                        <h3>Delete Post</h3>
                        <p v-if="postToDelete">Are you sure you want to delete "<strong>{{ postToDelete.title }}</strong>"?</p>
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
    </AdminLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import { useToast } from 'primevue/usetoast'
import AdminLayout from '@/Components/Admin/AdminLayout.vue'
import Card from 'primevue/card'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import InputText from 'primevue/inputtext'
import Button from 'primevue/button'
import Badge from 'primevue/badge'
import Dialog from 'primevue/dialog'

const props = defineProps({
    posts: Object,
    filters: Object
})

const toast = useToast()

// State
const searchQuery = ref(props.filters.search || '')
const loading = ref(false)
const deleting = ref(false)
const deleteDialog = ref(false)
const postToDelete = ref(null)

// Methods
const onSearch = () => {
    loading.value = true
    router.get(route('admin.posts.index'), {
        search: searchQuery.value
    }, {
        preserveState: true,
        onFinish: () => loading.value = false
    })
}

const clearSearch = () => {
    searchQuery.value = ''
    onSearch()
}

const onPage = (event) => {
    loading.value = true
    router.get(route('admin.posts.index'), {
        page: event.page + 1,
        search: searchQuery.value
    }, {
        preserveState: true,
        onFinish: () => loading.value = false
    })
}

const onSort = (event) => {
    loading.value = true
    router.get(route('admin.posts.index'), {
        sortField: event.sortField,
        sortOrder: event.sortOrder,
        search: searchQuery.value
    }, {
        preserveState: true,
        onFinish: () => loading.value = false
    })
}

const confirmDelete = (post) => {
    postToDelete.value = post
    deleteDialog.value = true
}

const deletePost = () => {
    if (!postToDelete.value) return
    
    deleting.value = true
    
    router.delete(route('admin.posts.destroy', postToDelete.value.id), {
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: 'Post deleted successfully',
                life: 3000
            })
            deleteDialog.value = false
            postToDelete.value = null
        },
        onError: () => {
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: 'Failed to delete post',
                life: 3000
            })
        },
        onFinish: () => deleting.value = false
    })
}

const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', { 
        year: 'numeric',
        month: 'short', 
        day: 'numeric'
    })
}
</script>

<style scoped>
.posts-index {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem;
}

/* Header */
.posts-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.posts-title {
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

.posts-subtitle {
    color: rgba(255, 255, 255, 0.8);
    font-size: 1.1rem;
}

.create-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: linear-gradient(135deg, var(--p-primary-color), var(--p-primary-600));
    color: white;
    text-decoration: none;
    border-radius: 2rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.create-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(147, 51, 234, 0.4);
}

/* Cards */
.filters-card,
.table-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 1rem;
    margin-bottom: 2rem;
}

/* Filters */
.filters {
    padding: 1rem;
}

.search-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    max-width: 400px;
}

.search-input {
    flex: 1;
}

:deep(.search-input) {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    border-radius: 2rem;
    padding: 0.75rem 1rem 0.75rem 2.5rem;
}

:deep(.search-input:focus) {
    border-color: var(--p-primary-color);
    box-shadow: 0 0 0 2px rgba(147, 51, 234, 0.2);
}

:deep(.search-input::placeholder) {
    color: rgba(255, 255, 255, 0.5);
}

:deep(.p-input-icon-left > i) {
    color: rgba(255, 255, 255, 0.6);
    left: 1rem;
}

/* DataTable */
:deep(.cosmic-datatable) {
    background: transparent;
}

:deep(.cosmic-datatable .p-datatable-header) {
    background: rgba(255, 255, 255, 0.05);
    border: none;
    color: white;
}

:deep(.cosmic-datatable .p-datatable-thead > tr > th) {
    background: rgba(255, 255, 255, 0.1);
    border: none;
    color: white;
    font-weight: 600;
    padding: 1rem;
}

:deep(.cosmic-datatable .p-datatable-tbody > tr) {
    background: transparent;
    transition: all 0.3s ease;
}

:deep(.cosmic-datatable .p-datatable-tbody > tr:hover) {
    background: rgba(255, 255, 255, 0.05);
}

:deep(.cosmic-datatable .p-datatable-tbody > tr > td) {
    border: none;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    color: white;
    padding: 1rem;
}

/* Table Columns */
.post-name strong {
    color: white;
    font-size: 1.1rem;
}

.post-slug {
    display: block;
    color: rgba(255, 255, 255, 0.6);
    font-family: monospace;
    font-size: 0.85rem;
    margin-top: 0.25rem;
}

.post-category,
.post-author {
    color: rgba(255, 255, 255, 0.8);
}

.date-info {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.9rem;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.action-btn {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s ease;
}

.view-btn {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
    border: 1px solid rgba(59, 130, 246, 0.3);
}

.edit-btn {
    background: rgba(147, 51, 234, 0.2);
    color: var(--p-primary-color);
    border: 1px solid rgba(147, 51, 234, 0.3);
}

.delete-btn:not(:disabled) {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.action-btn:hover:not(:disabled) {
    transform: translateY(-2px);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
}

.empty-icon {
    font-size: 4rem;
    color: rgba(255, 255, 255, 0.3);
    margin-bottom: 1rem;
}

.empty-state h3 {
    color: white;
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 2rem;
}

.empty-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: linear-gradient(135deg, var(--p-primary-color), var(--p-primary-600));
    color: white;
    text-decoration: none;
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
    .posts-index {
        padding: 1rem;
    }

    .posts-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }

    .posts-title {
        font-size: 2rem;
    }

    .search-group {
        max-width: 100%;
    }

    .action-buttons {
        flex-direction: column;
    }
}

/* Dark mode adjustments */
:global(.dark) .filters-card,
:global(.dark) .table-card {
    background: rgba(0, 0, 0, 0.3);
    border-color: rgba(255, 255, 255, 0.1);
}
</style>