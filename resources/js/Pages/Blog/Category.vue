<template>
    <Head :title="`${category.name} - Blog`" />
    
    <CosmicLayout 
        :title="category.name" 
        background-variant="default"
    >
        <div class="category-container">
            <!-- Category Header -->
            <section class="category-hero">
                <div class="category-icon">
                    <i class="pi pi-tag"></i>
                </div>
                <h1 class="category-title">
                    Exploring the 
                    <span class="cosmic-gradient-text">{{ category.name }}</span>
                    Constellation
                </h1>
                <p class="category-description">
                    Discover {{ posts.total }} cosmic insights in this category
                </p>
                
                <!-- Back to Blog Link -->
                <Link 
                    :href="route('blog.index')"
                    class="back-link"
                >
                    <i class="pi pi-arrow-left"></i>
                    Back to All Posts
                </Link>
            </section>

            <!-- Other Categories -->
            <section class="other-categories">
                <h2 class="section-title">Explore Other Constellations</h2>
                <div class="categories-grid">
                    <Link
                        v-for="cat in categories"
                        :key="cat.id"
                        :href="route('blog.category', cat.slug)"
                        class="category-card"
                        :class="{ 'category-card-active': cat.id === category.id }"
                    >
                        <div class="category-card-icon">
                            <i class="pi pi-sparkles"></i>
                        </div>
                        <div class="category-card-content">
                            <h3 class="category-card-name">{{ cat.name }}</h3>
                            <span class="category-card-count">
                                {{ cat.posts_count }} {{ cat.posts_count === 1 ? 'post' : 'posts' }}
                            </span>
                        </div>
                    </Link>
                </div>
            </section>

            <!-- Posts Grid -->
            <section class="category-posts">
                <div class="posts-grid">
                    <Card 
                        v-for="post in posts.data" 
                        :key="post.id"
                        class="post-card cosmic-card"
                    >
                        <template #header>
                            <div class="post-header">
                                <div class="post-date">
                                    <i class="pi pi-calendar"></i>
                                    {{ formatDate(post.published_at) }}
                                </div>
                                <div class="post-author">
                                    <i class="pi pi-user"></i>
                                    {{ post.user.name }}
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
                            <Link 
                                :href="route('blog.show', post.slug)"
                                class="post-read-more"
                            >
                                Continue Reading
                                <i class="pi pi-arrow-right"></i>
                            </Link>
                        </template>
                    </Card>
                </div>

                <!-- Empty State -->
                <div v-if="posts.data.length === 0" class="empty-state">
                    <i class="pi pi-inbox empty-state-icon"></i>
                    <h3 class="empty-state-title">No Posts Yet</h3>
                    <p class="empty-state-text">
                        This constellation is still forming. Check back soon for new content!
                    </p>
                    <Link 
                        :href="route('blog.index')"
                        class="empty-state-link"
                    >
                        <i class="pi pi-arrow-left"></i>
                        Browse All Posts
                    </Link>
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
import CosmicLayout from '@/Components/Cosmic/CosmicLayout.vue'
import Card from 'primevue/card'
import Paginator from 'primevue/paginator'

const props = defineProps({
    category: Object,
    posts: Object,
    categories: Array
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

const truncateText = (text, length) => {
    const stripped = text.replace(/<[^>]+>/g, '')
    if (stripped.length <= length) return stripped
    return stripped.substr(0, length).trim() + '...'
}

const handlePageChange = (event) => {
    router.get(route('blog.category', props.category.slug), { page: event.page + 1 }, {
        preserveState: true,
        preserveScroll: false
    })
}
</script>

<style scoped>
.category-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem;
}

/* Category Hero */
.category-hero {
    text-align: center;
    margin-bottom: 4rem;
    padding: 2rem 0;
}

.category-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--p-primary-color), var(--p-accent-color));
    border-radius: 50%;
    margin-bottom: 1.5rem;
    font-size: 2rem;
    color: white;
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.category-title {
    font-size: 2.5rem;
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

.category-description {
    font-size: 1.25rem;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 2rem;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--p-primary-color);
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.back-link:hover {
    color: var(--p-accent-color);
    gap: 0.75rem;
}

/* Other Categories */
.other-categories {
    margin-bottom: 3rem;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: white;
    text-align: center;
    margin-bottom: 2rem;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
}

.category-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 1rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.category-card:hover {
    transform: translateY(-4px);
    background: rgba(147, 51, 234, 0.2);
    border-color: var(--p-primary-color);
}

.category-card-active {
    background: linear-gradient(135deg, var(--p-primary-color), var(--p-primary-600));
    border-color: transparent;
}

.category-card-icon {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.category-card-active .category-card-icon {
    background: rgba(255, 255, 255, 0.3);
}

.category-card-content {
    flex: 1;
}

.category-card-name {
    color: white;
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.category-card-count {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.9rem;
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

.post-date, .post-author {
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

.post-read-more {
    display: inline-flex;
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
    margin-bottom: 2rem;
}

.empty-state-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: linear-gradient(135deg, var(--p-primary-color), var(--p-primary-600));
    border-radius: 2rem;
    color: white;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.empty-state-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(147, 51, 234, 0.4);
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
    .category-container {
        padding: 1rem;
    }

    .category-title {
        font-size: 2rem;
    }

    .category-description {
        font-size: 1.1rem;
    }

    .categories-grid {
        grid-template-columns: 1fr;
    }

    .posts-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .post-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .post-title-link {
        font-size: 1.25rem;
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

:global(.dark) .category-card {
    background: rgba(0, 0, 0, 0.3);
    border-color: rgba(255, 255, 255, 0.1);
}

:global(.dark) .category-card:hover {
    background: rgba(147, 51, 234, 0.3);
}
</style>