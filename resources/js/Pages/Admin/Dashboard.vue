<template>
    <Head title="Admin Dashboard" />
    
    <CosmicLayout 
        title="Admin Dashboard" 
        background-variant="default"
    >
        <div class="admin-dashboard">
            <!-- Dashboard Header -->
            <div class="dashboard-header">
                <h1 class="dashboard-title">
                    Cosmic 
                    <span class="cosmic-gradient-text">Control Center</span>
                </h1>
                <p class="dashboard-subtitle">
                    Monitor your stellar content and manage the cosmic archives
                </p>
                <div class="dashboard-actions">
                    <Link 
                        :href="route('admin.posts.create')"
                        class="action-btn action-btn-primary"
                    >
                        <i class="pi pi-plus"></i>
                        New Post
                    </Link>
                    <Link 
                        :href="route('admin.posts.index')"
                        class="action-btn action-btn-secondary"
                    >
                        <i class="pi pi-list"></i>
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

            <!-- Charts and Recent Activity -->
            <div class="dashboard-content">
                <!-- Monthly Stats Chart -->
                <Card class="chart-card">
                    <template #title>
                        <h3 class="card-title">
                            <i class="pi pi-chart-line"></i>
                            Monthly Post Activity
                        </h3>
                    </template>
                    <template #content>
                        <div class="chart-container">
                            <Chart 
                                type="line" 
                                :data="chartData" 
                                :options="chartOptions"
                                class="cosmic-chart"
                            />
                        </div>
                    </template>
                </Card>

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
        </div>
    </CosmicLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { computed } from 'vue'
import CosmicLayout from '@/Components/Cosmic/CosmicLayout.vue'
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
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem;
}

/* Dashboard Header */
.dashboard-header {
    text-align: center;
    margin-bottom: 3rem;
}

.dashboard-title {
    font-size: 3rem;
    font-weight: 800;
    color: white;
    margin-bottom: 0.5rem;
}

.cosmic-gradient-text {
    background: linear-gradient(135deg, var(--p-primary-color), var(--p-accent-color));
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: shimmer 3s ease-in-out infinite alternate;
}

@keyframes shimmer {
    0% { filter: hue-rotate(0deg); }
    100% { filter: hue-rotate(30deg); }
}

.dashboard-subtitle {
    color: rgba(255, 255, 255, 0.8);
    font-size: 1.1rem;
    margin-bottom: 2rem;
}

.dashboard-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 2rem;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.action-btn-primary {
    background: linear-gradient(135deg, var(--p-primary-color), var(--p-primary-600));
    color: white;
    border: none;
}

.action-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(147, 51, 234, 0.4);
}

.action-btn-secondary {
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.action-btn-secondary:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: var(--p-primary-color);
}

/* Statistics Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
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
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
}

.stat-card-primary { border-left: 4px solid var(--p-primary-color); }
.stat-card-success { border-left: 4px solid #10b981; }
.stat-card-warning { border-left: 4px solid #f59e0b; }
.stat-card-info { border-left: 4px solid #3b82f6; }
.stat-card-accent { border-left: 4px solid var(--p-accent-color); }

.stat-content {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
}

.stat-icon {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: var(--p-primary-color);
}

.stat-value {
    font-size: 2.5rem;
    font-weight: 800;
    color: white;
    line-height: 1;
}

.stat-label {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Dashboard Content */
.dashboard-content {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr;
    gap: 2rem;
}

.chart-card,
.recent-posts-card,
.categories-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 1rem;
}

.card-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: white;
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0;
}

.chart-container {
    height: 300px;
}

/* Recent Posts */
.recent-posts-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.recent-post-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

.recent-post-item:hover {
    background: rgba(255, 255, 255, 0.1);
}

.post-title {
    color: white;
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    line-height: 1.3;
}

.post-meta {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.post-category,
.post-date {
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.85rem;
}

.post-actions {
    display: flex;
    gap: 0.5rem;
}

.action-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    color: white;
    text-decoration: none;
    transition: all 0.3s ease;
}

.action-link:hover {
    background: var(--p-primary-color);
    transform: scale(1.1);
}

/* Categories */
.categories-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.category-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 0.5rem;
}

.category-icon {
    width: 40px;
    height: 40px;
    background: var(--p-primary-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.category-info {
    flex: 1;
}

.category-name {
    color: white;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.category-count {
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.8rem;
}

.category-progress {
    width: 80px;
}

/* Empty States */
.empty-state {
    text-align: center;
    padding: 2rem;
    color: rgba(255, 255, 255, 0.5);
}

.empty-state i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

/* Responsive */
@media (max-width: 1200px) {
    .dashboard-content {
        grid-template-columns: 1fr 1fr;
    }
    
    .chart-card {
        grid-column: span 2;
    }
}

@media (max-width: 768px) {
    .admin-dashboard {
        padding: 1rem;
    }

    .dashboard-title {
        font-size: 2rem;
    }

    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    }

    .dashboard-content {
        grid-template-columns: 1fr;
    }

    .chart-container {
        height: 250px;
    }

    .post-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
}

/* Dark mode adjustments */
:global(.dark) .stat-card,
:global(.dark) .chart-card,
:global(.dark) .recent-posts-card,
:global(.dark) .categories-card {
    background: rgba(0, 0, 0, 0.3);
    border-color: rgba(255, 255, 255, 0.1);
}
</style>