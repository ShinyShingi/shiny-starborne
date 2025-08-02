<template>
    <Head :title="`${category.name} - Admin`" />
    
    <CosmicLayout 
        title="Category Details" 
        background-variant="default"
    >
        <div class="show-category">
            <!-- Header -->
            <div class="category-header">
                <div class="header-info">
                    <h1 class="category-title">
                        <span class="cosmic-gradient-text">{{ category.name }}</span>
                    </h1>
                    <p class="category-subtitle" v-if="category.description">
                        {{ category.description }}
                    </p>
                    <p class="category-subtitle" v-else>
                        No description provided
                    </p>
                </div>
                <div class="header-actions">
                    <Link 
                        :href="route('admin.categories.edit', category.id)"
                        class="edit-btn"
                    >
                        <i class="pi pi-pencil"></i>
                        Edit Category
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

            <!-- Category Stats -->
            <div class="stats-grid">
                <Card class="stat-card">
                    <template #content>
                        <div class="stat-content">
                            <div class="stat-icon stat-icon-primary">
                                <i class="pi pi-file"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-value">{{ category.posts?.length || 0 }}</div>
                                <div class="stat-label">Total Posts</div>
                            </div>
                        </div>
                    </template>
                </Card>

                <Card class="stat-card">
                    <template #content>
                        <div class="stat-content">
                            <div class="stat-icon stat-icon-secondary">
                                <i class="pi pi-calendar"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-value">{{ formatDate(category.created_at) }}</div>
                                <div class="stat-label">Created Date</div>
                            </div>
                        </div>
                    </template>
                </Card>

                <Card class="stat-card">
                    <template #content>
                        <div class="stat-content">
                            <div class="stat-icon stat-icon-accent">
                                <i class="pi pi-tag"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-value">{{ category.slug }}</div>
                                <div class="stat-label">URL Slug</div>
                            </div>
                        </div>
                    </template>
                </Card>
            </div>

            <!-- Category Details -->
            <Card class="details-card">
                <template #title>
                    <h3 class="details-title">
                        <i class="pi pi-info-circle"></i>
                        Category Information
                    </h3>
                </template>
                <template #content>
                    <div class="details-grid">
                        <div class="detail-item">
                            <span class="detail-label">Name:</span>
                            <span class="detail-value">{{ category.name }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Slug:</span>
                            <span class="detail-value detail-slug">{{ category.slug }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Description:</span>
                            <span class="detail-value">{{ category.description || 'No description' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Created:</span>
                            <span class="detail-value">{{ formatDateTime(category.created_at) }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Last Updated:</span>
                            <span class="detail-value">{{ formatDateTime(category.updated_at) }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Posts Count:</span>
                            <Badge 
                                :value="category.posts?.length || 0" 
                                :severity="category.posts?.length > 0 ? 'info' : 'secondary'"
                            />
                        </div>
                    </div>
                </template>
            </Card>

            <!-- Recent Posts -->
            <Card class="posts-card" v-if="category.posts && category.posts.length > 0">
                <template #title>
                    <h3 class="posts-title">
                        <i class="pi pi-file"></i>
                        Recent Posts ({{ category.posts.length }})
                    </h3>
                </template>
                <template #content>
                    <div class="posts-list">
                        <div 
                            v-for="post in category.posts" 
                            :key="post.id"
                            class="post-item"
                        >
                            <div class="post-info">
                                <h4 class="post-title">
                                    <Link 
                                        :href="route('admin.posts.show', post.id)"
                                        class="post-link"
                                    >
                                        {{ post.title }}
                                    </Link>
                                </h4>
                                <div class="post-meta">
                                    <span class="post-author">by {{ post.user.name }}</span>
                                    <span class="post-date">{{ formatDate(post.created_at) }}</span>
                                    <Badge 
                                        :value="post.status" 
                                        :severity="post.status === 'published' ? 'success' : 'warning'"
                                    />
                                </div>
                            </div>
                            <div class="post-actions">
                                <Link 
                                    :href="route('admin.posts.edit', post.id)"
                                    class="post-action-btn edit-btn"
                                    v-tooltip="'Edit Post'"
                                >
                                    <i class="pi pi-pencil"></i>
                                </Link>
                                <Link 
                                    :href="route('blog.show', post.slug)"
                                    class="post-action-btn view-btn"
                                    target="_blank"
                                    v-tooltip="'View Post'"
                                >
                                    <i class="pi pi-external-link"></i>
                                </Link>
                            </div>
                        </div>
                    </div>
                </template>
            </Card>

            <!-- Empty State for Posts -->
            <Card class="posts-card" v-else>
                <template #content>
                    <div class="empty-posts">
                        <i class="pi pi-file empty-icon"></i>
                        <h3>No Posts Yet</h3>
                        <p>This category doesn't have any posts assigned to it yet.</p>
                        <Link 
                            :href="route('admin.posts.create')"
                            class="empty-action-btn"
                        >
                            <i class="pi pi-plus"></i>
                            Create First Post
                        </Link>
                    </div>
                </template>
            </Card>
        </div>
    </CosmicLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import CosmicLayout from '@/Components/Cosmic/CosmicLayout.vue'
import Card from 'primevue/card'
import Badge from 'primevue/badge'

const props = defineProps({
    category: Object
})

// Methods
const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', { 
        year: 'numeric',
        month: 'short', 
        day: 'numeric'
    })
}

const formatDateTime = (dateString) => {
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
.show-category {
    max-width: 1200px;
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

.edit-btn,
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

.edit-btn {
    background: linear-gradient(135deg, var(--p-primary-color), var(--p-primary-600));
    color: white;
}

.back-btn {
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.edit-btn:hover,
.back-btn:hover {
    transform: translateY(-2px);
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 1rem;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-4px);
    border-color: var(--p-primary-color);
}

.stat-content {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.stat-icon-primary {
    background: linear-gradient(135deg, var(--p-primary-color), var(--p-primary-600));
}

.stat-icon-secondary {
    background: linear-gradient(135deg, var(--p-secondary-color), var(--p-secondary-600));
}

.stat-icon-accent {
    background: linear-gradient(135deg, var(--p-accent-color), #be185d);
}

.stat-info {
    flex: 1;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 800;
    color: white;
    margin-bottom: 0.25rem;
}

.stat-label {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Cards */
.details-card,
.posts-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 1rem;
    margin-bottom: 2rem;
}

.details-title,
.posts-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: white;
    font-size: 1.2rem;
    font-weight: 600;
    margin: 0;
}

/* Details Grid */
.details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    padding: 1rem;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.detail-label {
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-value {
    color: white;
    font-weight: 500;
    word-break: break-word;
}

.detail-slug {
    font-family: monospace;
    background: rgba(255, 255, 255, 0.1);
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.9rem;
}

/* Posts List */
.posts-list {
    padding: 1rem;
}

.post-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 0.75rem;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.post-item:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateX(4px);
}

.post-item:last-child {
    margin-bottom: 0;
}

.post-title {
    color: white;
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.post-link {
    color: inherit;
    text-decoration: none;
    transition: color 0.3s ease;
}

.post-link:hover {
    color: var(--p-primary-color);
}

.post-meta {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.post-author,
.post-date {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.9rem;
}

.post-actions {
    display: flex;
    gap: 0.5rem;
}

.post-action-btn {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s ease;
}

.post-action-btn.edit-btn {
    background: rgba(147, 51, 234, 0.2);
    color: var(--p-primary-color);
    border: 1px solid rgba(147, 51, 234, 0.3);
}

.post-action-btn.view-btn {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
    border: 1px solid rgba(59, 130, 246, 0.3);
}

.post-action-btn:hover {
    transform: translateY(-2px);
}

/* Empty Posts */
.empty-posts {
    text-align: center;
    padding: 3rem 2rem;
}

.empty-icon {
    font-size: 4rem;
    color: rgba(255, 255, 255, 0.3);
    margin-bottom: 1rem;
}

.empty-posts h3 {
    color: white;
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.empty-posts p {
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
    transition: all 0.3s ease;
}

.empty-action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(147, 51, 234, 0.4);
}

/* Responsive */
@media (max-width: 768px) {
    .show-category {
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

    .stats-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .details-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .post-item {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }

    .post-actions {
        align-self: flex-end;
    }
}

/* Dark mode adjustments */
:global(.dark) .stat-card,
:global(.dark) .details-card,
:global(.dark) .posts-card {
    background: rgba(0, 0, 0, 0.3);
    border-color: rgba(255, 255, 255, 0.1);
}

:global(.dark) .post-item {
    background: rgba(0, 0, 0, 0.2);
}

:global(.dark) .post-item:hover {
    background: rgba(0, 0, 0, 0.4);
}
</style>