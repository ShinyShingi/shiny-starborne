<template>
    <Head title="Manage Posts - Admin" />
    
    <CosmicLayout 
        title="Post Management" 
        background-variant="default"
    >
        <div class="admin-posts">
            <!-- Header -->
            <div class="posts-header">
                <div class="header-info">
                    <h1 class="posts-title">
                        Manage 
                        <span class="cosmic-gradient-text">Stellar Content</span>
                    </h1>
                    <p class="posts-subtitle">
                        Create, edit, and organize your cosmic archives
                    </p>
                </div>
                <div class="header-actions">
                    <Link 
                        :href="route('admin.posts.create')"
                        class="create-btn"
                    >
                        <i class="pi pi-plus"></i>
                        Create Post
                    </Link>
                </div>
            </div>

            <!-- Filters -->
            <Card class="filters-card">
                <template #content>
                    <div class="filters-grid">
                        <div class="filter-group">
                            <label class="filter-label">Search</label>
                            <InputText
                                v-model="filters.search"
                                placeholder="Search posts..."
                                class="filter-input"
                                @input="debouncedSearch"
                            />
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Status</label>
                            <Dropdown
                                v-model="filters.status"
                                :options="statusOptions"
                                option-label="label"
                                option-value="value"
                                placeholder="All Statuses"
                                class="filter-dropdown"
                                @change="applyFilters"
                            />
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Category</label>
                            <Dropdown
                                v-model="filters.category"
                                :options="categoryOptions"
                                option-label="name"
                                option-value="id"
                                placeholder="All Categories"
                                class="filter-dropdown"
                                @change="applyFilters"
                            />
                        </div>
                        <div class="filter-actions">
                            <Button 
                                @click="clearFilters"
                                label="Clear"
                                icon="pi pi-times"
                                outlined
                                class="clear-btn"
                            />
                        </div>
                    </div>
                </template>
            </Card>

            <!-- Data Table -->
            <Card class="table-card">
                <template #content>
                    <DataTable
                        :value="posts.data"
                        :lazy="true"
                        :paginator="true"
                        :rows="posts.per_page"
                        :totalRecords="posts.total"
                        :loading="loading"
                        :sortField="sortField"
                        :sortOrder="sortOrder"
                        @page="onPage"
                        @sort="onSort"
                        :pt="{
                            root: { class: 'cosmic-datatable' },
                            header: { class: 'cosmic-datatable-header' },
                            paginator: { class: 'cosmic-paginator' }
                        }"
                        class="cosmic-table"
                        stripedRows
                        responsiveLayout="scroll"
                    >
                        <Column field="title" header="Title" sortable class="title-column">
                            <template #body="{ data }">
                                <div class="post-title-cell">
                                    <h4 class="post-title">{{ data.title }}</h4>
                                    <p class="post-slug">{{ data.slug }}</p>
                                </div>
                            </template>
                        </Column>

                        <Column field="status" header="Status" sortable>
                            <template #body="{ data }">
                                <Badge 
                                    :value="data.status" 
                                    :severity="data.status === 'published' ? 'success' : 'warning'"
                                    class="status-badge"
                                />
                            </template>
                        </Column>

                        <Column field="category.name" header="Category" sortable>
                            <template #body="{ data }">
                                <div class="category-cell">
                                    <i class="pi pi-tag"></i>
                                    {{ data.category.name }}
                                </div>
                            </template>
                        </Column>

                        <Column field="user.name" header="Author" sortable>
                            <template #body="{ data }">
                                <div class="author-cell">
                                    <i class="pi pi-user"></i>
                                    {{ data.user.name }}
                                </div>
                            </template>
                        </Column>

                        <Column field="created_at" header="Created" sortable>
                            <template #body="{ data }">
                                <div class="date-cell">
                                    {{ formatDate(data.created_at) }}
                                </div>
                            </template>
                        </Column>

                        <Column field="published_at" header="Published" sortable>
                            <template #body="{ data }">
                                <div class="date-cell">
                                    {{ data.published_at ? formatDate(data.published_at) : '-' }}
                                </div>
                            </template>
                        </Column>

                        <Column header="Actions" class="actions-column">
                            <template #body="{ data }">
                                <div class="action-buttons">
                                    <Button 
                                        @click="viewPost(data)"
                                        icon="pi pi-eye"
                                        size="small"
                                        severity="info"
                                        outlined
                                        rounded
                                        v-tooltip="'View'"
                                    />
                                    <Button 
                                        @click="editPost(data)"
                                        icon="pi pi-pencil"
                                        size="small"
                                        severity="secondary"
                                        outlined
                                        rounded
                                        v-tooltip="'Edit'"
                                    />
                                    <Button 
                                        @click="confirmDelete(data)"
                                        icon="pi pi-trash"
                                        size="small"
                                        severity="danger"
                                        outlined
                                        rounded
                                        v-tooltip="'Delete'"
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
                        <p>Are you sure you want to delete "<strong>{{ postToDelete?.title }}</strong>"?</p>
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
                            class="delete-btn"
                            :loading="deleting"
                        />
                    </div>
                </template>
            </Dialog>
        </div>
    </CosmicLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import { ref, reactive, computed, watch } from 'vue'
import { useToast } from 'primevue/usetoast'
import { debounce } from 'lodash'
import CosmicLayout from '@/Components/Cosmic/CosmicLayout.vue'
import Card from 'primevue/card'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Button from 'primevue/button'
import Badge from 'primevue/badge'
import InputText from 'primevue/inputtext'
import Dropdown from 'primevue/dropdown'
import Dialog from 'primevue/dialog'
import Tooltip from 'primevue/tooltip'

const props = defineProps({
    posts: Object,
    categories: Array,
    filters: Object
})

const toast = useToast()

// State
const loading = ref(false)
const deleting = ref(false)
const deleteDialog = ref(false)
const postToDelete = ref(null)
const sortField = ref('created_at')
const sortOrder = ref(-1)

// Reactive filters
const filters = reactive({
    search: props.filters.search || '',
    status: props.filters.status || null,
    category: props.filters.category || null
})

// Options
const statusOptions = [
    { label: 'Published', value: 'published' },
    { label: 'Draft', value: 'draft' }
]

const categoryOptions = computed(() => [
    ...props.categories
])

// Debounced search
const debouncedSearch = debounce(() => {
    applyFilters()
}, 500)

// Methods
const applyFilters = () => {
    loading.value = true
    
    const params = {}
    if (filters.search) params.search = filters.search
    if (filters.status) params.status = filters.status
    if (filters.category) params.category = filters.category

    router.get(route('admin.posts.index'), params, {
        preserveState: true,
        preserveScroll: true,
        onFinish: () => loading.value = false
    })
}

const clearFilters = () => {
    filters.search = ''
    filters.status = null
    filters.category = null
    applyFilters()
}

const onPage = (event) => {
    loading.value = true
    const params = {
        page: event.page + 1,
        ...getCurrentFilters()
    }
    
    router.get(route('admin.posts.index'), params, {
        preserveState: true,
        preserveScroll: true,
        onFinish: () => loading.value = false
    })
}

const onSort = (event) => {
    loading.value = true
    sortField.value = event.sortField
    sortOrder.value = event.sortOrder
    
    const params = {
        sortField: event.sortField,
        sortOrder: event.sortOrder,
        ...getCurrentFilters()
    }
    
    router.get(route('admin.posts.index'), params, {
        preserveState: true,
        preserveScroll: true,
        onFinish: () => loading.value = false
    })
}

const getCurrentFilters = () => {
    const params = {}
    if (filters.search) params.search = filters.search
    if (filters.status) params.status = filters.status
    if (filters.category) params.category = filters.category
    return params
}

const viewPost = (post) => {
    window.open(route('blog.show', post.slug), '_blank')
}

const editPost = (post) => {
    router.visit(route('admin.posts.edit', post.id))
}

const confirmDelete = (post) => {
    postToDelete.value = post
    deleteDialog.value = true
}

const deletePost = () => {
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
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}
</script>

<style scoped>
.admin-posts {
    max-width: 1600px;
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

/* Filters */
.filters-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 1rem;
    margin-bottom: 2rem;
}

.filters-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr auto;
    gap: 1.5rem;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-label {
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
}

.filter-input,
.filter-dropdown {
    width: 100%;
}

:deep(.filter-input) {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
}

:deep(.filter-input::placeholder) {
    color: rgba(255, 255, 255, 0.5);
}

/* Table */
.table-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 1rem;
}

:deep(.cosmic-datatable) {
    background: transparent;
    color: white;
}

:deep(.cosmic-datatable .p-datatable-thead > tr > th) {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.2);
    color: white;
    font-weight: 600;
}

:deep(.cosmic-datatable .p-datatable-tbody > tr) {
    background: transparent;
    border-color: rgba(255, 255, 255, 0.1);
}

:deep(.cosmic-datatable .p-datatable-tbody > tr:nth-child(even)) {
    background: rgba(255, 255, 255, 0.05);
}

:deep(.cosmic-datatable .p-datatable-tbody > tr:hover) {
    background: rgba(147, 51, 234, 0.2);
}

:deep(.cosmic-datatable .p-datatable-tbody > tr > td) {
    border-color: rgba(255, 255, 255, 0.1);
    color: white;
}

/* Table Cells */
.post-title-cell {
    max-width: 300px;
}

.post-title {
    color: white;
    font-weight: 600;
    margin-bottom: 0.25rem;
    line-height: 1.3;
}

.post-slug {
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.85rem;
    font-family: monospace;
}

.category-cell,
.author-cell {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: rgba(255, 255, 255, 0.8);
}

.date-cell {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.9rem;
}

.status-badge {
    font-size: 0.85rem;
}

/* Actions */
.action-buttons {
    display: flex;
    gap: 0.5rem;
}

/* Delete Dialog */
.cosmic-dialog :deep(.p-dialog) {
    background: rgba(0, 0, 0, 0.9);
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
@media (max-width: 1200px) {
    .filters-grid {
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    
    .filter-actions {
        grid-column: span 2;
        justify-self: start;
    }
}

@media (max-width: 768px) {
    .admin-posts {
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

    .filters-grid {
        grid-template-columns: 1fr;
    }

    .filter-actions {
        grid-column: span 1;
    }
}

/* Dark mode adjustments */
:global(.dark) .filters-card,
:global(.dark) .table-card {
    background: rgba(0, 0, 0, 0.3);
    border-color: rgba(255, 255, 255, 0.1);
}
</style>