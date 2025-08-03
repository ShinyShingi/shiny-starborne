<template>
    <Head title="Home - Shiny Starborne" />
    
    <CosmicLayout 
        title="Shiny Starborne" 
        background-variant="hero"
        :show-header="true"
    >
        <!-- Hero Section -->
        <v-container fluid class="py-16 text-center position-relative overflow-hidden">
            <div class="cosmic-particles">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="hero-content">
                <h1 class="hero-title">
                    Welcome to the
                    <span class="cosmic-gradient-text">Cosmic Era</span>
                </h1>
                <p class="hero-subtitle">
                    Explore our stellar content, discover cosmic insights, and join our interstellar community
                </p>
                <div class="hero-actions">
                    <v-btn 
                        v-if="!$page.props.auth.user && canLogin"
                        @click="() => $inertia.visit(route('login'))"
                        size="large"
                        color="primary"
                        prepend-icon="mdi-login"
                        class="cosmic-btn-primary"
                    >
                        Sign In
                    </v-btn>
                    <v-btn 
                        v-if="!$page.props.auth.user && canRegister"
                        @click="() => $inertia.visit(route('register'))"
                        size="large"
                        variant="outlined"
                        prepend-icon="mdi-account-plus"
                        class="cosmic-btn-secondary"
                    >
                        Join the Cosmos
                    </v-btn>
                    <v-btn 
                        @click="() => $inertia.visit(route('blog.index'))"
                        size="large"
                        :color="$page.props.auth.user ? 'primary' : ''"
                        :variant="!$page.props.auth.user ? 'outlined' : 'elevated'"
                        prepend-icon="mdi-book-open-variant"
                        :class="$page.props.auth.user ? 'cosmic-btn-primary' : 'cosmic-btn-secondary'"
                    >
                        Explore Blog
                    </v-btn>
                </div>
            </div>
        </v-container>

        <!-- Latest Posts Section -->
        <v-container class="py-16" style="max-width: 1400px;">
            <div class="section-header">
                <h2 class="section-title">
                    Latest from the 
                    <span class="cosmic-gradient-text">Stellar Archives</span>
                </h2>
                <Link 
                    :href="route('blog.index')"
                    class="section-link"
                >
                    View All Posts
                    <v-icon size="small" class="ml-1">mdi-arrow-right</v-icon>
                </Link>
            </div>

            <v-row class="mt-8">
                <v-col
                    v-for="post in latestPosts" 
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
                            <v-card-item>
                                <div class="post-header">
                                    <v-chip size="small" class="post-category">
                                        {{ post.category.name }}
                                    </v-chip>
                                    <span class="post-date">
                                        {{ formatDate(post.published_at) }}
                                    </span>
                                </div>
                            </v-card-item>
                            
                            <v-card-title class="post-title">
                                {{ post.title }}
                            </v-card-title>
                            
                            <v-card-text>
                                <p class="post-excerpt">{{ truncateText(post.content, 120) }}</p>
                                <div class="post-footer mt-4">
                                    <span class="post-author">
                                        <v-icon size="small" class="mr-1">mdi-account</v-icon>
                                        {{ post.user.name }}
                                    </span>
                                    <v-icon size="small" class="post-read-indicator">
                                        mdi-arrow-right
                                    </v-icon>
                                </div>
                            </v-card-text>
                        </v-card>
                    </Link>
                </v-col>
            </v-row>

            <div v-if="latestPosts.length === 0" class="empty-posts text-center py-16">
                <v-icon size="x-large" class="empty-icon mb-4">mdi-inbox</v-icon>
                <p class="empty-text">No cosmic insights available yet</p>
            </div>
        </v-container>

        <!-- Categories Section -->
        <div class="py-16 bg-cosmic-bg-secondary">
            <v-container style="max-width: 1400px;">
                <div class="section-header">
                    <h2 class="section-title">
                        Explore Our 
                        <span class="cosmic-gradient-text">Constellations</span>
                    </h2>
                </div>

                <v-row class="mt-8">
                    <v-col
                        v-for="category in categories"
                        :key="category.id"
                        cols="12"
                        sm="6"
                        md="4"
                        lg="3"
                    >
                        <Link
                            :href="route('blog.category', category.slug)"
                            class="text-decoration-none"
                        >
                            <v-card class="category-tile category-tile-hover text-center pa-6">
                                <div class="category-icon mb-3">
                                    <v-icon size="x-large">mdi-sparkles</v-icon>
                                </div>
                                <h3 class="category-name">{{ category.name }}</h3>
                                <p class="category-count mt-2">
                                    {{ category.posts_count }} 
                                    {{ category.posts_count === 1 ? 'article' : 'articles' }}
                                </p>
                            </v-card>
                        </Link>
                    </v-col>
                </v-row>
            </v-container>
        </div>
    </CosmicLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import CosmicLayout from '@/Components/Cosmic/CosmicLayout.vue'

const props = defineProps({
    latestPosts: Array,
    categories: Array,
    totalPosts: Number,
    canLogin: Boolean,
    canRegister: Boolean
})

// Methods
const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', { 
        month: 'short', 
        day: 'numeric' 
    })
}

const truncateText = (text, length) => {
    const stripped = text.replace(/<[^>]+>/g, '')
    if (stripped.length <= length) return stripped
    return stripped.substr(0, length).trim() + '...'
}
</script>

<style scoped>
/* Hero Section */
.hero-content {
    position: relative;
    z-index: 10;
}

.hero-title {
    font-size: clamp(2.5rem, 6vw, 4.5rem);
    font-weight: 800;
    margin-bottom: 1.5rem;
    line-height: 1.1;
}

.hero-subtitle {
    font-size: clamp(1.1rem, 2vw, 1.5rem);
    color: rgb(var(--cosmic-text-secondary));
    margin-bottom: 3rem;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.hero-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

/* Section Headers */
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.section-title {
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 700;
}

.section-link {
    color: rgb(var(--cosmic-primary));
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    transition: all 0.3s ease;
}

.section-link:hover {
    color: rgb(var(--cosmic-primary-hover));
    transform: translateX(5px);
}

/* Post Cards */
.post-card {
    height: 100%;
    transition: all 0.3s ease;
}

.post-card-link {
    text-decoration: none;
    display: block;
    color: inherit;
    height: 100%;
}

.post-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.post-category {
    background: linear-gradient(135deg, rgb(var(--cosmic-primary)), rgb(var(--cosmic-secondary)));
    color: white;
}

.post-date {
    font-size: 0.875rem;
    color: rgb(var(--cosmic-text-secondary));
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

/* Category Tiles */
.category-tile {
    transition: all 0.3s ease;
    height: 100%;
}

.category-tile:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(var(--cosmic-primary), 0.3);
}

.category-icon {
    color: rgb(var(--cosmic-primary));
}

.category-name {
    font-size: 1.25rem;
    font-weight: 600;
    color: rgb(var(--cosmic-text));
}

.category-count {
    color: rgb(var(--cosmic-text-secondary));
    font-size: 0.875rem;
}

/* Empty State */
.empty-posts {
    padding: 4rem 2rem;
}

.empty-icon {
    color: rgb(var(--cosmic-text-secondary));
    opacity: 0.5;
}

.empty-text {
    color: rgb(var(--cosmic-text-secondary));
    font-size: 1.125rem;
}


/* Background Section */
.bg-cosmic-bg-secondary {
    background-color: rgba(var(--cosmic-bg-secondary), 0.3);
}

/* Responsive */
@media (max-width: 768px) {
    .section-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .hero-actions {
        flex-direction: column;
        align-items: center;
    }
}
</style>