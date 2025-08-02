<template>
    <Head title="Blog - Explore the Cosmos" />
    
    <CosmicLayout 
        title="Cosmic Blog" 
        background-variant="default"
    >
        <div class="blog-container">
            <!-- Hero Section -->
            <section class="blog-hero">
                <h1 class="blog-hero-title">
                    Journey Through the 
                    <span class="cosmic-gradient-text">Stellar Archives</span>
                </h1>
                <p class="blog-hero-subtitle">
                    Discover insights, tutorials, and cosmic wisdom from our interstellar community
                </p>
            </section>

            <!-- Categories Filter -->
            <section class="blog-categories">
                <div class="categories-header">
                    <h2 class="categories-title">Explore by Constellation</h2>
                </div>
                <div class="categories-grid">
                    <Link
                        :href="route('blog.index')"
                        class="category-chip"
                        :class="{ 'category-chip-active': !$page.url.includes('/category/') }"
                    >
                        <i class="pi pi-globe"></i>
                        <span>All Posts</span>
                        <Badge :value="totalPosts" class="category-badge" />
                    </Link>
                    <Link
                        v-for="category in categories"
                        :key="category.id"
                        :href="route('blog.category', category.slug)"
                        class="category-chip"
                        :class="{ 'category-chip-active': $page.url.includes(`/category/${category.slug}`) }"
                    >
                        <i class="pi pi-tag"></i>
                        <span>{{ category.name }}</span>
                        <Badge :value="category.posts_count" class="category-badge" />
                    </Link>
                </div>
            </section>

            <!-- Posts Grid -->
            <section class="blog-posts">
                <div class="posts-grid">
                    <Card 
                        v-for="post in posts.data" 
                        :key="post.id"
                        class="post-card cosmic-card"
                    >
                        <template #header>
                            <div class="post-header">
                                <div class="post-category">
                                    <Link 
                                        :href="route('blog.category', post.category.slug)"
                                        class="post-category-link"
                                    >
                                        {{ post.category.name }}
                                    </Link>
                                </div>
                                <div class="post-date">
                                    <i class="pi pi-calendar"></i>
                                    {{ formatDate(post.published_at) }}
                                </div>
                            </div>
                        </template>
                        
                        <template #title>
                            <Link 
                                :href="route('blog.show', post.slug)"
                                class="post-title-link"
                            >
                                {{ post.title }}
                            </Link>
                        </template>
                        
                        <template #content>
                            <p class="post-excerpt">{{ truncateText(post.content, 150) }}</p>
                            <div class="post-meta">
                                <div class="post-author">
                                    <i class="pi pi-user"></i>
                                    <span>{{ post.user.name }}</span>
                                </div>
                                <Link 
                                    :href="route('blog.show', post.slug)"
                                    class="post-read-more"
                                >
                                    Read More
                                    <i class="pi pi-arrow-right"></i>
                                </Link>
                            </div>
                        </template>
                    </Card>
                </div>

                <!-- Empty State -->
                <div v-if="posts.data.length === 0" class="empty-state">
                    <i class="pi pi-inbox empty-state-icon"></i>
                    <h3 class="empty-state-title">No Posts Found</h3>
                    <p class="empty-state-text">
                        The cosmic archives are empty at the moment. Check back soon!
                    </p>
                </div>

                <!-- Pagination -->
                <div v-if="posts.last_page > 1" class="pagination-wrapper">
                    <Paginator 
                        :first="(posts.current_page - 1) * posts.per_page"
                        :rows="posts.per_page"
                        :totalRecords="posts.total"
                        @page="handlePageChange"
                        :pt="{
                            root: { class: 'cosmic-paginator' },
                            pages: { class: 'cosmic-paginator-pages' },
                            firstPageButton: { class: 'cosmic-paginator-button' },
                            previousPageButton: { class: 'cosmic-paginator-button' },
                            nextPageButton: { class: 'cosmic-paginator-button' },
                            lastPageButton: { class: 'cosmic-paginator-button' },
                            pageButton: { class: 'cosmic-paginator-page' }
                        }"
                    />
                </div>
            </section>
        </div>
    </CosmicLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import { computed } from 'vue'
import CosmicLayout from '@/Components/Cosmic/CosmicLayout.vue'
import Card from 'primevue/card'
import Badge from 'primevue/badge'
import Paginator from 'primevue/paginator'

const props = defineProps({
    posts: Object,
    categories: Array
})

// Computed
const totalPosts = computed(() => {
    return props.categories.reduce((sum, cat) => sum + cat.posts_count, 0)
})

// Methods
const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    })
}

const truncateText = (text, length) => {
    const stripped = text.replace(/<[^>]+>/g, '')
    if (stripped.length <= length) return stripped
    return stripped.substr(0, length).trim() + '...'
}

const handlePageChange = (event) => {
    router.get(route('blog.index'), { page: event.page + 1 }, {
        preserveState: true,
        preserveScroll: false
    })
}
</script>

<style scoped>
.blog-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem;
}

/* Hero Section */
.blog-hero {
    text-align: center;
    margin-bottom: 4rem;
    padding: 2rem 0;
}

.blog-hero-title {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 1rem;
    color: white;
    line-height: 1.2;
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

.blog-hero-subtitle {
    font-size: 1.25rem;
    color: rgba(255, 255, 255, 0.8);
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

/* Categories Section */
.blog-categories {
    margin-bottom: 3rem;
}

.categories-header {
    text-align: center;
    margin-bottom: 2rem;
}

.categories-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: white;
}

.categories-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: center;
}

.category-chip {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 2rem;
    color: white;
    text-decoration: none;
    transition: all 0.3s ease;
}

.category-chip:hover {
    background: rgba(147, 51, 234, 0.2);
    border-color: var(--p-primary-color);
    transform: translateY(-2px);
}

.category-chip-active {
    background: linear-gradient(135deg, var(--p-primary-color), var(--p-primary-600));
    border-color: transparent;
}

.category-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    background: rgba(255, 255, 255, 0.2);
}

/* Posts Grid */
.posts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.post-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 1rem;
    transition: all 0.3s ease;
    overflow: hidden;
}

.post-card:hover {
    transform: translateY(-8px);
    border-color: var(--p-primary-color);
    box-shadow: 0 20px 40px rgba(147, 51, 234, 0.3);
}

.post-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    background: rgba(0, 0, 0, 0.2);
}

.post-category-link {
    color: var(--p-primary-color);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: color 0.3s ease;
}

.post-category-link:hover {
    color: var(--p-accent-color);
}

.post-date {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.85rem;
}

.post-title-link {
    color: white;
    text-decoration: none;
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1.3;
    display: block;
    margin-bottom: 1rem;
    transition: color 0.3s ease;
}

.post-title-link:hover {
    color: var(--p-primary-color);
}

.post-excerpt {
    color: rgba(255, 255, 255, 0.8);
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.post-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.post-author {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.9rem;
}

.post-read-more {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--p-primary-color);
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.post-read-more:hover {
    color: var(--p-accent-color);
    gap: 0.75rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-state-icon {
    font-size: 4rem;
    color: rgba(255, 255, 255, 0.3);
    margin-bottom: 1rem;
}

.empty-state-title {
    font-size: 1.5rem;
    color: white;
    margin-bottom: 0.5rem;
}

.empty-state-text {
    color: rgba(255, 255, 255, 0.7);
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 3rem;
}

:deep(.cosmic-paginator) {
    background: transparent;
    border: none;
}

:deep(.cosmic-paginator-button),
:deep(.cosmic-paginator-page) {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: white;
    margin: 0 0.25rem;
    transition: all 0.3s ease;
}

:deep(.cosmic-paginator-button:hover),
:deep(.cosmic-paginator-page:hover) {
    background: rgba(147, 51, 234, 0.2);
    border-color: var(--p-primary-color);
}

:deep(.cosmic-paginator-page.p-highlight) {
    background: linear-gradient(135deg, var(--p-primary-color), var(--p-primary-600));
    border-color: transparent;
}

/* Responsive Design */
@media (max-width: 768px) {
    .blog-container {
        padding: 1rem;
    }

    .blog-hero-title {
        font-size: 2rem;
    }

    .blog-hero-subtitle {
        font-size: 1.1rem;
    }

    .posts-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .category-chip {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }

    .post-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .post-title-link {
        font-size: 1.25rem;
    }

    .post-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
}

/* Dark mode adjustments */
:global(.dark) .post-card {
    background: rgba(0, 0, 0, 0.3);
    border-color: rgba(255, 255, 255, 0.1);
}

:global(.dark) .post-card:hover {
    border-color: var(--p-primary-color);
}

:global(.dark) .category-chip {
    background: rgba(0, 0, 0, 0.3);
    border-color: rgba(255, 255, 255, 0.1);
}

:global(.dark) .category-chip:hover {
    background: rgba(147, 51, 234, 0.3);
}
</style>