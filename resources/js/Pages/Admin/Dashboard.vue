<template>
    <Head title="Admin Dashboard" />
    
    <AdminLayout>
        <div class="admin-dashboard">
            <!-- Dashboard Header -->
            <div class="dashboard-header">
                <h1 class="dashboard-title">Dashboard</h1>
                <p class="dashboard-subtitle">
                    Overview of your blog content and statistics
                </p>
                <div class="dashboard-actions">
                    <Link 
                        :href="route('admin.posts.create')"
                        class="action-btn action-btn-primary"
                    >
                        + New Post
                    </Link>
                    <Link 
                        :href="route('admin.posts.index')"
                        class="action-btn action-btn-secondary"
                    >
                        Manage Posts
                    </Link>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <Card class="stat-card stat-card-primary">
                    <template #content>
                        <div class="stat-content">
                            <div class="stat-icon">
                                <i class="pi pi-file-edit"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-value">{{ stats.total_posts }}</div>
                                <div class="stat-label">Total Posts</div>
                            </div>
                        </div>
                    </template>
                </Card>

                <Card class="stat-card stat-card-success">
                    <template #content>
                        <div class="stat-content">
                            <div class="stat-icon">
                                <i class="pi pi-check-circle"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-value">{{ stats.published_posts }}</div>
                                <div class="stat-label">Published</div>
                            </div>
                        </div>
                    </template>
                </Card>

                <Card class="stat-card stat-card-warning">
                    <template #content>
                        <div class="stat-content">
                            <div class="stat-icon">
                                <i class="pi pi-clock"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-value">{{ stats.draft_posts }}</div>
                                <div class="stat-label">Drafts</div>
                            </div>
                        </div>
                    </template>
                </Card>

                <Card class="stat-card stat-card-info">
                    <template #content>
                        <div class="stat-content">
                            <div class="stat-icon">
                                <i class="pi pi-tags"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-value">{{ stats.total_categories }}</div>
                                <div class="stat-label">Categories</div>
                            </div>
                        </div>
                    </template>
                </Card>

                <Card class="stat-card stat-card-accent">
                    <template #content>
                        <div class="stat-content">
                            <div class="stat-icon">
                                <i class="pi pi-users"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-value">{{ stats.total_users }}</div>
                                <div class="stat-label">Users</div>
                            </div>
                        </div>
                    </template>
                </Card>
            </div>

            <!-- Recent Activity -->
            <div class="dashboard-content">
                <!-- Recent Posts -->
                <Card class="recent-posts-card">
                    <template #title>
                        <h3 class="card-title">
                            <i class="pi pi-clock"></i>
                            Recent Posts
                        </h3>
                    </template>
                    <template #content>
                        <div class="recent-posts-list">
                            <div 
                                v-for="post in recent_posts" 
                                :key="post.id"
                                class="recent-post-item"
                            >
                                <div class="post-info">
                                    <h4 class="post-title">{{ post.title }}</h4>
                                    <div class="post-meta">
                                        <Badge 
                                            :value="post.status" 
                                            :severity="post.status === 'published' ? 'success' : 'warning'"
                                            class="post-status"
                                        />
                                        <span class="post-category">{{ post.category.name }}</span>
                                        <span class="post-date">{{ formatDate(post.created_at) }}</span>
                                    </div>
                                </div>
                                <div class="post-actions">
                                    <Link 
                                        :href="route('admin.posts.edit', post.id)"
                                        class="action-link"
                                        v-tooltip="'Edit'"
                                    >
                                        <i class="pi pi-pencil"></i>
                                    </Link>
                                    <Link 
                                        :href="route('blog.show', post.slug)"
                                        class="action-link"
                                        v-tooltip="'View'"
                                        target="_blank"
                                    >
                                        <i class="pi pi-external-link"></i>
                                    </Link>
                                </div>
                            </div>
                            
                            <div v-if="recent_posts.length === 0" class="empty-state">
                                <i class="pi pi-inbox"></i>
                                <p>No posts yet</p>
                            </div>
                        </div>
                    </template>
                </Card>

                <!-- Popular Categories -->
                <Card class="categories-card">
                    <template #title>
                        <h3 class="card-title">
                            <i class="pi pi-star"></i>
                            Popular Categories
                        </h3>
                    </template>
                    <template #content>
                        <div class="categories-list">
                            <div 
                                v-for="category in popular_categories" 
                                :key="category.id"
                                class="category-item"
                            >
                                <div class="category-icon">
                                    <i class="pi pi-tag"></i>
                                </div>
                                <div class="category-info">
                                    <h4 class="category-name">{{ category.name }}</h4>
                                    <span class="category-count">{{ category.posts_count }} posts</span>
                                </div>
                                <ProgressBar 
                                    :value="getCategoryPercentage(category.posts_count)" 
                                    class="category-progress"
                                />
                            </div>
                            
                            <div v-if="popular_categories.length === 0" class="empty-state">
                                <i class="pi pi-tag"></i>
                                <p>No categories yet</p>
                            </div>
                        </div>
                    </template>
                </Card>
            </div>

            <!-- Analytics Accordion -->
            <div class="analytics-section">
                <details class="analytics-accordion">
                    <summary class="accordion-header">
                        <i class="pi pi-chart-line"></i>
                        Monthly Post Activity
                        <i class="pi pi-chevron-down accordion-chevron"></i>
                    </summary>
                    <div class="accordion-content">
                        <div class="chart-container">
                            <Chart 
                                type="line" 
                                :data="chartData" 
                                :options="chartOptions"
                                class="cosmic-chart"
                            />
                        </div>
                    </div>
                </details>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { computed } from 'vue'
import AdminLayout from '@/Components/Admin/AdminLayout.vue'
import Card from 'primevue/card'
import Badge from 'primevue/badge'
import Chart from 'primevue/chart'
import ProgressBar from 'primevue/progressbar'
import Tooltip from 'primevue/tooltip'

const props = defineProps({
    stats: Object,
    recent_posts: Array,
    popular_categories: Array,
    monthly_stats: Array
})

// Chart data
const chartData = computed(() => {
    const labels = props.monthly_stats.map(stat => {
        const date = new Date(stat.month + '-01')
        return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' })
    })
    
    const data = props.monthly_stats.map(stat => stat.count)
    
    return {
        labels,
        datasets: [
            {
                label: 'Posts Created',
                data,
                borderColor: '#9333ea',
                backgroundColor: 'rgba(147, 51, 234, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }
        ]
    }
})

const chartOptions = computed(() => ({
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            labels: {
                color: 'rgba(255, 255, 255, 0.8)',
                font: {
                    family: 'Inter, sans-serif'
                }
            }
        }
    },
    scales: {
        x: {
            ticks: {
                color: 'rgba(255, 255, 255, 0.6)'
            },
            grid: {
                color: 'rgba(255, 255, 255, 0.1)'
            }
        },
        y: {
            ticks: {
                color: 'rgba(255, 255, 255, 0.6)'
            },
            grid: {
                color: 'rgba(255, 255, 255, 0.1)'
            }
        }
    }
}))

// Methods
const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', { 
        month: 'short', 
        day: 'numeric',
        year: 'numeric'
    })
}

const getCategoryPercentage = (count) => {
    const maxCount = Math.max(...props.popular_categories.map(cat => cat.posts_count))
    return maxCount > 0 ? (count / maxCount) * 100 : 0
}
</script>

<style scoped>
.admin-dashboard {
    margin: 0;
}

/* Dashboard Header */
.dashboard-header {
    margin-bottom: 2rem;
}

.dashboard-title {
    font-size: 2rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

:global(.dark) .dashboard-title {
    color: #f9fafb;
}

.dashboard-subtitle {
    color: #6b7280;
    font-size: 1rem;
    margin-bottom: 1.5rem;
}

:global(.dark) .dashboard-subtitle {
    color: #9ca3af;
}

.dashboard-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    text-decoration: none;
    font-weight: 500;
}

.action-btn-primary {
    background-color: #dc2626;
    color: white;
    border: 1px solid #dc2626;
}

.action-btn-primary:hover {
    background-color: #b91c1c;
    border-color: #b91c1c;
}

.action-btn-secondary {
    background-color: transparent;
    color: #374151;
    border: 1px solid #d1d5db;
}

.action-btn-secondary:hover {
    background-color: #f3f4f6;
    border-color: #9ca3af;
}

:global(.dark) .action-btn-secondary {
    color: #d1d5db;
    border-color: #4b5563;
}

:global(.dark) .action-btn-secondary:hover {
    background-color: #374151;
    border-color: #6b7280;
}

/* Statistics Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background-color: white;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
}

:global(.dark) .stat-card {
    background-color: #1f2937;
    border-color: #374151;
}

.stat-card-primary { border-left: 4px solid #dc2626; }
.stat-card-success { border-left: 4px solid #10b981; }
.stat-card-warning { border-left: 4px solid #f59e0b; }
.stat-card-info { border-left: 4px solid #3b82f6; }
.stat-card-accent { border-left: 4px solid #8b5cf6; }

.stat-content {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
}

.stat-icon {
    width: 48px;
    height: 48px;
    background-color: #f3f4f6;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: #6b7280;
}

:global(.dark) .stat-icon {
    background-color: #374151;
    color: #9ca3af;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: #1f2937;
    line-height: 1;
}

:global(.dark) .stat-value {
    color: #f9fafb;
}

.stat-label {
    color: #6b7280;
    font-size: 0.875rem;
    font-weight: 500;
}

:global(.dark) .stat-label {
    color: #9ca3af;
}

/* Dashboard Content */
.dashboard-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.recent-posts-card,
.categories-card {
    background-color: white;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
}

:global(.dark) .recent-posts-card,
:global(.dark) .categories-card {
    background-color: #1f2937;
    border-color: #374151;
}

/* Analytics Section */
.analytics-section {
    margin-top: 2rem;
}

.analytics-accordion {
    background-color: white;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    overflow: hidden;
}

:global(.dark) .analytics-accordion {
    background-color: #1f2937;
    border-color: #374151;
}

.accordion-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
    padding: 1rem;
    font-weight: 500;
    cursor: pointer;
    list-style: none;
    user-select: none;
    color: #1f2937;
    background-color: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}

.accordion-header::-webkit-details-marker {
    display: none;
}

:global(.dark) .accordion-header {
    color: #f9fafb;
    background-color: #111827;
    border-bottom-color: #374151;
}

.accordion-header:hover {
    background-color: #f3f4f6;
}

:global(.dark) .accordion-header:hover {
    background-color: #0f172a;
}

.accordion-chevron {
    transition: transform 0.2s ease;
}

.analytics-accordion[open] .accordion-chevron {
    transform: rotate(180deg);
}

.accordion-content {
    padding: 1.5rem;
}

.card-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #1f2937;
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0;
}

:global(.dark) .card-title {
    color: #f9fafb;
}

.chart-container {
    height: 300px;
}

/* Recent Posts */
.recent-posts-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.recent-post-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background-color: #f9fafb;
    border-radius: 0.375rem;
}

:global(.dark) .recent-post-item {
    background-color: #111827;
}


.post-title {
    color: #1f2937;
    font-size: 0.875rem;
    font-weight: 500;
    margin-bottom: 0.25rem;
    line-height: 1.25;
}

:global(.dark) .post-title {
    color: #f9fafb;
}

.post-meta {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.post-category,
.post-date {
    color: #6b7280;
    font-size: 0.75rem;
}

:global(.dark) .post-category,
:global(.dark) .post-date {
    color: #9ca3af;
}

.post-actions {
    display: flex;
    gap: 0.5rem;
}

.action-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 0.25rem;
    background-color: #f3f4f6;
    color: #6b7280;
    text-decoration: none;
}

.action-link:hover {
    background-color: #dc2626;
    color: white;
}

:global(.dark) .action-link {
    background-color: #374151;
    color: #9ca3af;
}

:global(.dark) .action-link:hover {
    background-color: #dc2626;
    color: white;
}

/* Categories */
.categories-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.category-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background-color: #f9fafb;
    border-radius: 0.375rem;
}

:global(.dark) .category-item {
    background-color: #111827;
}

.category-icon {
    width: 32px;
    height: 32px;
    background-color: #dc2626;
    border-radius: 0.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.875rem;
}

.category-info {
    flex: 1;
}

.category-name {
    color: #1f2937;
    font-size: 0.875rem;
    font-weight: 500;
    margin-bottom: 0.125rem;
}

:global(.dark) .category-name {
    color: #f9fafb;
}

.category-count {
    color: #6b7280;
    font-size: 0.75rem;
}

:global(.dark) .category-count {
    color: #9ca3af;
}

.category-progress {
    width: 60px;
}

/* Empty States */
.empty-state {
    text-align: center;
    padding: 2rem;
    color: #9ca3af;
}

.empty-state i {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-title {
        font-size: 1.5rem;
    }

    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    }

    .dashboard-content {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .chart-container {
        height: 250px;
    }

    .post-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
}
</style>