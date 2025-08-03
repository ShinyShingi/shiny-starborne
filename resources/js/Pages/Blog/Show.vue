<template>
    <Head :title="post.title" />
    
    <CosmicLayout 
        :title="post.title" 
        background-variant="hero"
    >
        <v-container class="post-container" style="max-width: 900px;">
            <!-- Post Header -->
            <header class="post-header text-center mb-12">
                <div class="post-meta-top">
                    <Link 
                        :href="route('blog.category', post.category.slug)"
                        class="text-decoration-none"
                    >
                        <v-chip 
                            color="primary"
                            size="large"
                            prepend-icon="mdi-tag"
                        >
                            {{ post.category.name }}
                        </v-chip>
                    </Link>
                    <div class="post-date">
                        <v-icon size="small" class="mr-1">mdi-calendar</v-icon>
                        {{ formatDate(post.published_at) }}
                    </div>
                </div>
                
                <h1 class="post-title">{{ post.title }}</h1>
                
                <div class="post-author-info">
                    <v-avatar class="author-avatar" color="primary">
                        <v-icon color="white">mdi-account</v-icon>
                    </v-avatar>
                    <div class="author-details">
                        <div class="author-name">{{ post.user.name }}</div>
                        <div class="author-role">Cosmic Explorer</div>
                    </div>
                </div>
            </header>

            <!-- Post Content -->
            <div class="post-content">
                <v-card class="content-card mb-8">
                    <v-card-text>
                        <div class="prose" v-html="post.content"></div>
                    </v-card-text>
                </v-card>
            </div>

            <!-- Share Section -->
            <div class="post-share text-center mb-8">
                <h3 class="share-title mb-4">Share this cosmic insight</h3>
                <div class="share-buttons">
                    <v-tooltip text="Copy link" location="bottom">
                        <template v-slot:activator="{ props }">
                            <v-btn
                                v-bind="props"
                                icon="mdi-link-variant"
                                variant="outlined"
                                @click="copyLink"
                            />
                        </template>
                    </v-tooltip>
                    <v-tooltip text="Share on Twitter" location="bottom">
                        <template v-slot:activator="{ props }">
                            <v-btn
                                v-bind="props"
                                icon="mdi-twitter"
                                variant="outlined"
                                @click="shareOnTwitter"
                            />
                        </template>
                    </v-tooltip>
                    <v-tooltip text="Share on Facebook" location="bottom">
                        <template v-slot:activator="{ props }">
                            <v-btn
                                v-bind="props"
                                icon="mdi-facebook"
                                variant="outlined"
                                @click="shareOnFacebook"
                            />
                        </template>
                    </v-tooltip>
                </div>
            </div>

            <!-- Navigation -->
            <div class="post-navigation mb-12">
                <Link 
                    :href="route('blog.index')"
                    class="nav-link nav-back"
                >
                    <v-icon size="small" class="mr-1">mdi-arrow-left</v-icon>
                    Back to Blog
                </Link>
                <Link 
                    :href="route('blog.category', post.category.slug)"
                    class="nav-link nav-category"
                >
                    More in {{ post.category.name }}
                    <v-icon size="small" class="ml-1">mdi-arrow-right</v-icon>
                </Link>
            </div>

            <!-- Related Posts -->
            <section v-if="relatedPosts.length > 0" class="related-posts">
                <h2 class="related-title text-center mb-8">
                    Continue Your 
                    <span class="cosmic-gradient-text">Cosmic Journey</span>
                </h2>
                <v-row>
                    <v-col
                        v-for="relatedPost in relatedPosts" 
                        :key="relatedPost.id"
                        cols="12"
                        md="6"
                        lg="4"
                    >
                        <v-card class="related-card h-100">
                            <v-card-title>
                                <Link 
                                    :href="route('blog.show', relatedPost.slug)"
                                    class="related-card-title"
                                >
                                    {{ relatedPost.title }}
                                </Link>
                            </v-card-title>
                            <v-card-text>
                                <p class="related-excerpt">
                                    {{ truncateText(relatedPost.content, 100) }}
                                </p>
                                <div class="related-meta">
                                    <span class="related-date">
                                        {{ formatDate(relatedPost.published_at) }}
                                    </span>
                                    <Link 
                                        :href="route('blog.show', relatedPost.slug)"
                                        class="related-read-more"
                                    >
                                        Read More
                                        <v-icon size="small" class="ml-1">mdi-arrow-right</v-icon>
                                    </Link>
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </section>
        </v-container>
        
        <!-- Snackbar for notifications -->
        <v-snackbar
            v-model="snackbar"
            :color="snackbarColor"
            timeout="3000"
            location="top right"
        >
            {{ snackbarText }}
        </v-snackbar>
    </CosmicLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import CosmicLayout from '@/Components/Cosmic/CosmicLayout.vue'

const props = defineProps({
    post: Object,
    relatedPosts: Array
})

// Snackbar state
const snackbar = ref(false)
const snackbarText = ref('')
const snackbarColor = ref('success')

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

const copyLink = () => {
    navigator.clipboard.writeText(window.location.href)
    snackbarText.value = 'Post link copied to clipboard'
    snackbarColor.value = 'success'
    snackbar.value = true
}

const shareOnTwitter = () => {
    const url = encodeURIComponent(window.location.href)
    const text = encodeURIComponent(props.post.title)
    window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank')
}

const shareOnFacebook = () => {
    const url = encodeURIComponent(window.location.href)
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank')
}
</script>

<style scoped>
/* Post Header */
.post-meta-top {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 2rem;
    margin-bottom: 1.5rem;
}

.post-date {
    display: flex;
    align-items: center;
    color: rgb(var(--cosmic-text-secondary));
    font-size: 0.9rem;
}

.post-title {
    font-size: clamp(2.5rem, 5vw, 3.5rem);
    font-weight: 800;
    color: rgb(var(--cosmic-text));
    margin-bottom: 2rem;
    line-height: 1.2;
}

.post-author-info {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
}

.author-avatar {
    width: 50px;
    height: 50px;
}

.author-details {
    text-align: left;
}

.author-name {
    font-weight: 600;
    color: rgb(var(--cosmic-text));
    font-size: 1.1rem;
}

.author-role {
    color: rgb(var(--cosmic-text-secondary));
    font-size: 0.9rem;
}

/* Post Content */
.content-card {
    /* Styles will come from global v-card rules */
}

.prose {
    color: rgb(var(--cosmic-text));
    line-height: 1.8;
    font-size: 1.1rem;
}

.prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
    color: rgb(var(--cosmic-text));
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.prose h2 {
    font-size: 2rem;
    font-weight: 700;
}

.prose h3 {
    font-size: 1.5rem;
    font-weight: 600;
}

.prose p {
    margin-bottom: 1.5rem;
}

.prose ul, .prose ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.prose li {
    margin-bottom: 0.5rem;
}

.prose a {
    color: rgb(var(--cosmic-primary));
    text-decoration: underline;
}

.prose a:hover {
    color: rgb(var(--cosmic-accent));
}

.prose blockquote {
    border-left: 4px solid rgb(var(--cosmic-primary));
    padding-left: 1.5rem;
    margin: 2rem 0;
    font-style: italic;
    color: rgb(var(--cosmic-text-secondary));
}

.prose code {
    background: rgba(var(--cosmic-bg-secondary), 0.5);
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-family: monospace;
    font-size: 0.9em;
}

.prose pre {
    background: rgba(var(--cosmic-bg-secondary), 0.8);
    padding: 1.5rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    margin-bottom: 1.5rem;
}

.prose pre code {
    background: transparent;
    padding: 0;
}

/* Share Section */
.share-title {
    font-size: 1.25rem;
    color: rgb(var(--cosmic-text));
}

.share-buttons {
    display: flex;
    justify-content: center;
    gap: 1rem;
}

/* Navigation */
.post-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 1rem;
}

.nav-link {
    display: flex;
    align-items: center;
    color: rgb(var(--cosmic-primary));
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.nav-link:hover {
    color: rgb(var(--cosmic-accent));
}

.nav-back:hover {
    gap: 0.75rem;
}

.nav-category:hover {
    gap: 0.75rem;
}

/* Related Posts */
.related-title {
    font-size: 2rem;
    font-weight: 700;
    color: rgb(var(--cosmic-text));
}


.related-card {
    transition: all 0.3s ease;
    height: 100%;
}

.related-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 15px 30px rgba(var(--cosmic-primary), 0.3);
}

.related-card-title {
    color: rgb(var(--cosmic-text));
    text-decoration: none;
    font-size: 1.25rem;
    font-weight: 600;
    display: block;
    margin-bottom: 0.5rem;
    transition: color 0.3s ease;
}

.related-card-title:hover {
    color: rgb(var(--cosmic-primary));
}

.related-excerpt {
    color: rgb(var(--cosmic-text-secondary));
    line-height: 1.6;
    margin-bottom: 1rem;
}

.related-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.related-date {
    color: rgb(var(--cosmic-text-secondary));
    font-size: 0.85rem;
}

.related-read-more {
    display: flex;
    align-items: center;
    color: rgb(var(--cosmic-primary));
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.related-read-more:hover {
    color: rgb(var(--cosmic-accent));
}

/* Responsive Design */
@media (max-width: 768px) {
    .post-title {
        font-size: 2rem;
    }

    .post-meta-top {
        flex-direction: column;
        gap: 1rem;
    }

    .prose {
        font-size: 1rem;
    }

    .post-navigation {
        flex-direction: column;
        gap: 1rem;
    }

    .share-buttons {
        flex-wrap: wrap;
    }
}
</style>