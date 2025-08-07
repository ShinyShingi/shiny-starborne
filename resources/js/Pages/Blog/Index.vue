<template>
    <Head title="Blog - Explore the Cosmos" />
    
    <CosmicLayout 
        title="Cosmic Blog" 
        background-variant="default"
    >
        <v-container class="blog-container" style="max-width: 1400px;">
            <!-- Hero Section -->
            <section class="blog-hero text-center mb-12 position-relative overflow-hidden">
                <div class="cosmic-particles">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <h1 class="hero-title">
                    Journey Through the 
                    <span class="cosmic-gradient-text">Stellar Archives</span>
                </h1>
                <p class="hero-subtitle">
                    Discover insights, tutorials, and cosmic wisdom from our interstellar community
                </p>
            </section>

            <!-- Categories Filter -->
            <section class="blog-categories mb-12">
                <h2 class="section-title text-center mb-6">Filter by Constellation</h2>
                <div class="categories-grid">
                    <v-chip
                        @click="selectCategory(null)"
                        :color="selectedCategory === null ? 'primary' : ''"
                        :variant="selectedCategory === null ? 'elevated' : 'outlined'"
                        size="large"
                        class="category-chip ma-1"
                    >
                        <v-icon start>mdi-earth</v-icon>
                        All Posts
                        <v-badge
                            :content="totalPosts"
                            inline
                            color="secondary"
                            class="ml-2"
                        />
                    </v-chip>
                    <v-chip
                        v-for="category in categories"
                        :key="category.id"
                        @click="selectCategory(category.id)"
                        :color="selectedCategory === category.id ? 'primary' : ''"
                        :variant="selectedCategory === category.id ? 'elevated' : 'outlined'"
                        size="large"
                        class="category-chip ma-1"
                    >
                        <v-icon start>mdi-tag</v-icon>
                        {{ category.name }}
                        <v-badge
                            :content="category.posts_count"
                            inline
                            color="secondary"
                            class="ml-2"
                        />
                    </v-chip>
                </div>
            </section>

            <!-- Posts Grid -->
            <section class="blog-posts">
                <div class="filter-results-header mb-6">
                    <h3 class="filter-results-title">
                        {{ currentCategoryName }}
                        <span class="filter-results-count">({{ filteredPosts.length }} {{ filteredPosts.length === 1 ? 'post' : 'posts' }})</span>
                    </h3>
                </div>
                
                <v-row>
                    <v-col
                        v-for="post in filteredPosts" 
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
                                    >
                                        <div class="image-overlay">
                                            <v-chip size="small" class="post-category-overlay">
                                                {{ post.category.name }}
                                            </v-chip>
                                        </div>
                                    </v-img>
                                </div>
                                
                                <v-card-item>
                                    <div class="post-header" :class="{ 'no-image': !post.image }">
                                        <v-chip v-if="!post.image" size="small" class="post-category">
                                            {{ post.category.name }}
                                        </v-chip>
                                        <div class="post-date">
                                            <v-icon size="small" class="mr-1">mdi-calendar</v-icon>
                                            {{ formatDate(post.published_at) }}
                                        </div>
                                    </div>
                                </v-card-item>
                                
                                <v-card-title class="post-title">
                                    {{ post.title }}
                                </v-card-title>
                                
                                <v-card-text>
                                    <p class="post-excerpt">{{ post.clean_excerpt }}</p>
                                    <div class="post-footer mt-4">
                                        <div class="post-author">
                                            <v-icon size="small" class="mr-1">mdi-account</v-icon>
                                            <span>{{ post.user.name }}</span>
                                        </div>
                                        <v-icon size="small" class="post-read-indicator">
                                            mdi-arrow-right
                                        </v-icon>
                                    </div>
                                </v-card-text>
                            </v-card>
                        </Link>
                    </v-col>
                </v-row>

                <!-- Empty State -->
                <div v-if="filteredPosts.length === 0" class="empty-posts text-center py-16">
                    <v-icon size="x-large" class="empty-icon mb-4">mdi-inbox</v-icon>
                    <h3 class="empty-state-title">No Posts Found</h3>
                    <p class="empty-text">
                        <template v-if="selectedCategory">
                            No posts found in this constellation. Try exploring other categories!
                        </template>
                        <template v-else>
                            The cosmic archives are empty at the moment. Check back soon!
                        </template>
                    </p>
                    <v-btn 
                        v-if="selectedCategory"
                        @click="selectCategory(null)"
                        variant="outlined"
                        prepend-icon="mdi-earth"
                        class="mt-4"
                    >
                        Show All Posts
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
        </v-container>
    </CosmicLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import CosmicLayout from '@/Components/Cosmic/CosmicLayout.vue'

const props = defineProps({
    posts: Object,
    categories: Array
})

// State
const selectedCategory = ref(null)
const currentPage = ref(props.posts.current_page)

// Computed
const totalPosts = computed(() => {
    return props.categories.reduce((sum, cat) => sum + cat.posts_count, 0)
})

const filteredPosts = computed(() => {
    if (!selectedCategory.value) {
        return props.posts.data
    }
    return props.posts.data.filter(post => post.category.id === selectedCategory.value)
})

const currentCategoryName = computed(() => {
    if (!selectedCategory.value) return 'All Posts'
    const category = props.categories.find(cat => cat.id === selectedCategory.value)
    return category ? category.name : 'All Posts'
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
    // Decode HTML entities multiple times to handle double-encoding
    let decoded = text
    
    // Create a temporary element to decode HTML entities
    const temp = document.createElement('div')
    
    // Decode up to 3 times to handle multiple levels of encoding
    for (let i = 0; i < 3; i++) {
        temp.innerHTML = decoded
        const newDecoded = temp.textContent || temp.innerText || ''
        if (newDecoded === decoded) break // No more decoding needed
        decoded = newDecoded
    }
    
    // Strip any remaining HTML tags
    const stripped = decoded.replace(/<[^>]+>/g, '')
    
    if (stripped.length <= length) return stripped
    return stripped.substring(0, length).trim() + '...'
}

const handlePageChange = (page) => {
    router.get(route('blog.index'), { page }, {
        preserveState: true,
        preserveScroll: false
    })
}

const selectCategory = (categoryId) => {
    selectedCategory.value = categoryId
}
</script>

<style scoped>
/* Hero Section */
.blog-hero {
    padding: 2rem 0;
}

.hero-title {
    font-size: clamp(2.5rem, 5vw, 3.5rem);
    font-weight: 800;
    margin-bottom: 1.5rem;
    line-height: 1.1;
    color: rgb(var(--cosmic-text));
}

.hero-subtitle {
    font-size: clamp(1.1rem, 2vw, 1.5rem);
    color: rgb(var(--cosmic-text-secondary));
    max-width: 600px;
    margin: 0 auto;
}

/* Section Headers */
.section-title {
    font-size: clamp(1.75rem, 3vw, 2.5rem);
    font-weight: 700;
    color: rgb(var(--cosmic-text));
}

/* Categories */
.categories-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}

.category-chip {
    transition: all 0.3s ease;
    animation: fadeInUp 0.5s ease-out;
    animation-fill-mode: both;
}

.category-chip:nth-child(1) { animation-delay: 0.1s; }
.category-chip:nth-child(2) { animation-delay: 0.2s; }
.category-chip:nth-child(3) { animation-delay: 0.3s; }
.category-chip:nth-child(4) { animation-delay: 0.4s; }
.category-chip:nth-child(5) { animation-delay: 0.5s; }

.category-chip:hover {
    transform: translateY(-2px) scale(1.05);
}

/* Filter Results */
.filter-results-title {
    font-size: 1.75rem;
    font-weight: 600;
    color: rgb(var(--cosmic-text));
    margin: 0;
}

.filter-results-count {
    font-size: 1.25rem;
    font-weight: 400;
    color: rgb(var(--cosmic-text-secondary));
    margin-left: 0.5rem;
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

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, transparent 50%);
    display: flex;
    align-items: flex-start;
    justify-content: flex-start;
    padding: 1rem;
}

.post-category-overlay {
    background: linear-gradient(135deg, rgb(var(--cosmic-primary)), rgb(var(--cosmic-secondary)));
    color: white;
    backdrop-filter: blur(10px);
}

.post-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.post-header.no-image {
    justify-content: space-between;
}

.post-header:not(.no-image) {
    justify-content: flex-end;
}

.post-category {
    background: linear-gradient(135deg, rgb(var(--cosmic-primary)), rgb(var(--cosmic-secondary)));
    color: white;
}

.post-date {
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

.post-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.post-author {
    display: flex;
    align-items: center;
    color: rgb(var(--cosmic-text-secondary));
    font-size: 0.875rem;
}

.post-read-indicator {
    color: rgb(var(--cosmic-primary));
    transition: transform 0.3s ease;
}

.post-card-link:hover .post-read-indicator {
    transform: translateX(5px);
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
        gap: 0.5rem;
    }
}
</style>