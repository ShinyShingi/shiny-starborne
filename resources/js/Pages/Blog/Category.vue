<template>
    <Head :title="`${category.name} - Blog`" />
    
    <CosmicLayout 
        :title="category.name" 
        background-variant="default"
    >
        <v-container class="category-container" style="max-width: 1400px;">
            <!-- Category Header -->
            <section class="category-hero text-center mb-12 position-relative overflow-hidden">
                <div class="cosmic-particles">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <div class="category-icon">
                    <v-icon size="x-large" color="white">mdi-tag</v-icon>
                </div>
                <h1 class="hero-title">
                    Exploring the 
                    <span class="cosmic-gradient-text">{{ category.name }}</span>
                    Constellation
                </h1>
                <p class="hero-subtitle">
                    Discover {{ posts.total }} cosmic insights in this category
                </p>
                
                <!-- Back to Blog Link -->
                <Link 
                    :href="route('blog.index')"
                    class="back-link mt-4"
                >
                    <v-icon size="small" class="mr-1">mdi-arrow-left</v-icon>
                    Back to All Posts
                </Link>
            </section>

            <!-- Posts Grid -->
            <section class="category-posts">
                <v-row>
                    <v-col
                        v-for="post in posts.data" 
                        :key="post.id"
                        cols="12"
                        md="6"
                        lg="4"
                    >
                        <Link
                            :href="route('blog.show', post.slug)"
                            class="post-card-link"
                        >
                            <v-card class="post-card cosmic-card-hover h-100">
                                <!-- Featured Image -->
                                <div v-if="post.image" class="post-image-container">
                                    <v-img
                                        :src="post.image"
                                        :alt="post.title"
                                        height="200"
                                        cover
                                        class="post-featured-image"
                                    />
                                </div>
                                
                                <v-card-item>
                                    <div class="post-header">
                                        <div class="post-date">
                                            <v-icon size="small" class="mr-1">mdi-calendar</v-icon>
                                            {{ formatDate(post.published_at) }}
                                        </div>
                                        <div class="post-author">
                                            <v-icon size="small" class="mr-1">mdi-account</v-icon>
                                            {{ post.user.name }}
                                        </div>
                                    </div>
                                </v-card-item>
                                
                                <v-card-title class="post-title">
                                    {{ post.title }}
                                </v-card-title>
                                
                                <v-card-text>
                                    <p class="post-excerpt">{{ truncateText(post.content, 150) }}</p>
                                    <div class="post-read-indicator mt-4">
                                        <v-icon size="small">mdi-arrow-right</v-icon>
                                    </div>
                                </v-card-text>
                            </v-card>
                        </Link>
                    </v-col>
                </v-row>

                <!-- Empty State -->
                <div v-if="posts.data.length === 0" class="empty-posts text-center py-16">
                    <v-icon size="x-large" class="empty-icon mb-4">mdi-inbox</v-icon>
                    <h3 class="empty-state-title">No Posts Yet</h3>
                    <p class="empty-text">
                        This constellation is still forming. Check back soon for new content!
                    </p>
                    <v-btn
                        :href="route('blog.index')"
                        color="primary"
                        prepend-icon="mdi-arrow-left"
                        class="mt-4"
                    >
                        Browse All Posts
                    </v-btn>
                </div>

                <!-- Pagination -->
                <div v-if="posts.last_page > 1" class="pagination-wrapper">
                    <v-pagination
                        v-model="currentPage"
                        :length="posts.last_page"
                        :total-visible="7"
                        @update:model-value="handlePageChange"
                        class="cosmic-pagination"
                    />
                </div>
            </section>

            <!-- Other Categories -->
            <section class="other-categories-compact mt-12 pt-8">
                <h3 class="section-subtitle text-center mb-6">Explore Other Constellations</h3>
                <div class="category-chips-grid">
                    <Link
                        v-for="cat in categories"
                        :key="cat.id"
                        :href="route('blog.category', cat.slug)"
                        class="text-decoration-none"
                    >
                        <v-chip
                            :color="cat.id === category.id ? 'primary' : ''"
                            :variant="cat.id === category.id ? 'elevated' : 'outlined'"
                            size="large"
                            class="category-chip ma-1"
                        >
                            <v-icon start>mdi-tag</v-icon>
                            {{ cat.name }}
                            <span class="category-chip-count ml-1">({{ cat.posts_count }})</span>
                        </v-chip>
                    </Link>
                </div>
            </section>
        </v-container>
    </CosmicLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import CosmicLayout from '@/Components/Cosmic/CosmicLayout.vue'

const props = defineProps({
    category: Object,
    posts: Object,
    categories: Array
})

// State
const currentPage = ref(props.posts.current_page)

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

const handlePageChange = (page) => {
    router.get(route('blog.category', props.category.slug), { page }, {
        preserveState: true,
        preserveScroll: false
    })
}
</script>

<style scoped>
/* Category Hero */
.category-hero {
    padding: 2rem 0;
}

/* Hero Icon */
.category-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, rgb(var(--cosmic-primary)), rgb(var(--cosmic-accent)));
    border-radius: 50%;
    margin-bottom: 1.5rem;
    animation: pulse 2s ease-in-out infinite, rotate360 8s linear infinite;
    z-index: 1;
    position: relative;
}

.hero-title {
    font-size: clamp(2.5rem, 5vw, 3.5rem);
    font-weight: 800;
    margin-bottom: 1.5rem;
    line-height: 1.1;
    color: rgb(var(--cosmic-text));
    z-index: 1;
    position: relative;
}

.hero-subtitle {
    font-size: clamp(1.1rem, 2vw, 1.5rem);
    color: rgb(var(--cosmic-text-secondary));
    z-index: 1;
    position: relative;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: rgb(var(--cosmic-primary));
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.back-link:hover {
    color: rgb(var(--cosmic-accent));
    gap: 0.75rem;
}

/* Post Cards */
.post-card {
    height: 100%;
    transition: all 0.3s ease;
    overflow: hidden;
}

.post-card-link {
    text-decoration: none;
    display: block;
    color: inherit;
    height: 100%;
}

/* Featured Image */
.post-image-container {
    position: relative;
    overflow: hidden;
}

.post-featured-image {
    transition: transform 0.3s ease;
}

.post-card-link:hover .post-featured-image {
    transform: scale(1.05);
}

.post-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.post-date,
.post-author {
    font-size: 0.875rem;
    color: rgb(var(--cosmic-text-secondary));
    display: flex;
    align-items: center;
}

.post-title {
    color: rgb(var(--cosmic-text));
    transition: color 0.3s ease;
    font-size: 1.25rem;
    line-height: 1.3;
}

.post-card-link:hover .post-title {
    color: rgb(var(--cosmic-primary));
}

.post-excerpt {
    color: rgb(var(--cosmic-text-secondary));
    line-height: 1.6;
}

.post-read-indicator {
    color: rgb(var(--cosmic-primary));
    transition: transform 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: flex-end;
}

.post-card-link:hover .post-read-indicator {
    transform: translateX(5px);
}

/* Other Categories */
.other-categories-compact {
    border-top: 1px solid rgb(var(--cosmic-card-border));
}

.section-subtitle {
    font-size: 1.25rem;
    font-weight: 600;
    color: rgb(var(--cosmic-text));
}

.category-chips-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    max-width: 800px;
    margin: 0 auto;
}

.category-chip {
    transition: all 0.3s ease;
}

.category-chip:hover {
    transform: translateY(-2px);
}

.category-chip-count {
    font-size: 0.85rem;
    opacity: 0.8;
}

/* Empty State */
.empty-state-title {
    font-size: 1.5rem;
    color: rgb(var(--cosmic-text));
    margin-bottom: 0.5rem;
}

.empty-icon {
    color: rgb(var(--cosmic-text-secondary));
    opacity: 0.5;
}

.empty-text {
    color: rgb(var(--cosmic-text-secondary));
    font-size: 1.125rem;
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 3rem;
}


/* Responsive Design */
@media (max-width: 768px) {
    .post-header {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>