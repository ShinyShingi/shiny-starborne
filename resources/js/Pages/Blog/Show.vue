<template>
    <Head :title="post.title" />
    
    <CosmicLayout 
        :title="post.title" 
        background-variant="hero"
    >
        <v-container class="post-container" style="max-width: 900px;">
            <!-- Post Header -->
            <header class="post-header mb-6">
                <!-- Title at the very top -->
                <h1 class="post-title">{{ post.title }}</h1>
                
                <!-- One row with user, date, and category -->
                <div class="post-meta-row">
                    <div class="author-info">
                        <v-avatar class="author-avatar" color="primary" size="32">
                            <v-icon color="white">mdi-account</v-icon>
                        </v-avatar>
                        <span class="author-name">{{ post.user.name }}</span>
                    </div>
                    <div class="post-date">
                        <v-icon size="small" class="mr-1">mdi-calendar</v-icon>
                        {{ formatDate(post.published_at) }}
                    </div>
                    <div class="post-category">
                        <Link 
                            :href="route('blog.category', post.category.slug)"
                            class="text-decoration-none"
                        >
                            <v-chip 
                                color="primary"
                                size="small"
                                prepend-icon="mdi-tag"
                            >
                                {{ post.category.name }}
                            </v-chip>
                        </Link>
                    </div>
                </div>
            </header>

            <!-- Featured Image UNDER Title -->
            <div v-if="post.image" class="post-featured-image-container mb-8">
                <v-img
                    :src="post.image"
                    :alt="post.title"
                    width="100%"
                    class="post-hero-image"
                />
            </div>

            <!-- Post Content -->
            <article class="post-content mb-8">
                <div class="content-wrapper">
                    <div class="prose wysiwyg-content" v-html="post.content"></div>
                </div>
            </article>

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
                            <!-- Related Post Image -->
                            <div v-if="relatedPost.image" class="related-image-container">
                                <v-img
                                    :src="relatedPost.image"
                                    :alt="relatedPost.title"
                                    height="150"
                                    cover
                                    class="related-featured-image"
                                />
                            </div>
                            
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
.post-header {
    text-align: center;
    margin-bottom: 2rem;
    flex-direction: column;
}

.post-title {
    font-size: clamp(2rem, 5vw, 3.5rem);
    font-weight: 800;
    color: rgb(var(--cosmic-text));
    margin: 0 0 1.5rem 0;
    line-height: 1.3;
    text-align: center;
    word-wrap: break-word;
    hyphens: auto;
}

.post-meta-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 1rem;
    border: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    gap: 1rem;
}

.author-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex: 1;
}

.author-name {
    font-weight: 600;
    color: rgb(var(--cosmic-text));
    font-size: 0.9rem;
}

.post-date {
    display: flex;
    align-items: center;
    color: rgb(var(--cosmic-text-secondary));
    font-size: 0.85rem;
    flex: 1;
    justify-content: center;
}

.post-category {
    flex: 1;
    display: flex;
    justify-content: flex-end;
}

/* Featured Image */
.post-featured-image-container {
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
}

.post-hero-image {
    width: 100% !important;
    height: 100% !important;
    transition: transform 0.3s ease;
}

.post-hero-image :deep(.v-img__img) {
    width: 100% !important;
    height: 100% !important;
    object-fit: contain !important;
    display: block !important;
    opacity: 1 !important;
    visibility: visible !important;
}

/* Post Content */
.post-content {
    margin: 0 auto;
}

.content-wrapper {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 1rem;
    padding: 2.5rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

/* WYSIWYG Content Styling */
.wysiwyg-content {
    color: rgb(var(--cosmic-text));
    line-height: 1.8;
    font-size: 1.1rem;
    word-wrap: break-word;
    overflow-wrap: break-word;
    hyphens: auto;
}

/* Headings */
.wysiwyg-content h1,
.wysiwyg-content h2,
.wysiwyg-content h3,
.wysiwyg-content h4,
.wysiwyg-content h5,
.wysiwyg-content h6 {
    color: rgb(var(--cosmic-text));
    font-weight: bold;
    margin: 2.5rem 0 1.5rem 0;
    line-height: 1.3;
}

.wysiwyg-content h1 { font-size: 2.5rem; }
.wysiwyg-content h2 { font-size: 2rem; }
.wysiwyg-content h3 { font-size: 1.75rem; }
.wysiwyg-content h4 { font-size: 1.5rem; }
.wysiwyg-content h5 { font-size: 1.25rem; }
.wysiwyg-content h6 { font-size: 1.1rem; }

/* Paragraphs and text */
.wysiwyg-content p {
    margin-bottom: 1.5rem;
    text-align: left;
    word-wrap: break-word;
    overflow-wrap: break-word;
    padding: 0.2rem;
}

.wysiwyg-content strong {
    font-weight: bold;
    color: rgb(var(--cosmic-text));
}

.wysiwyg-content em {
    font-style: italic;
}

.wysiwyg-content u {
    text-decoration: underline;
}

.wysiwyg-content s {
    text-decoration: line-through;
}

/* Lists */
.wysiwyg-content ul,
.wysiwyg-content ol {
    margin: 1.5rem 0;
    padding-left: 2rem;
}

.wysiwyg-content ul {
    list-style-type: disc;
}

.wysiwyg-content ol {
    list-style-type: decimal;
}

.wysiwyg-content li {
    margin-bottom: 0.75rem;
    line-height: 1.6;
}

.wysiwyg-content ul ul,
.wysiwyg-content ol ol,
.wysiwyg-content ul ol,
.wysiwyg-content ol ul {
    margin: 0.5rem 0;
}

/* Links */
.wysiwyg-content a {
    color: rgb(var(--cosmic-primary));
    text-decoration: underline;
    transition: color 0.3s ease;
}

.wysiwyg-content a:hover {
    color: rgb(var(--cosmic-accent));
}

/* Blockquotes */
.wysiwyg-content blockquote {
    border-left: 4px solid rgb(var(--cosmic-primary));
    padding: 1.5rem 2rem;
    margin: 2rem 0;
    background: rgba(147, 51, 234, 0.1);
    border-radius: 0.5rem;
    font-style: italic;
    color: rgb(var(--cosmic-text-secondary));
}

.wysiwyg-content blockquote p {
    margin-bottom: 1rem;
}

.wysiwyg-content blockquote p:last-child {
    margin-bottom: 0;
}

/* Code */
.wysiwyg-content code {
    background: rgba(0, 0, 0, 0.3);
    color: #e2e8f0;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-family: 'Courier New', Courier, monospace;
    font-size: 0.9em;
}

.wysiwyg-content pre {
    background: rgba(0, 0, 0, 0.6);
    padding: 1.5rem;
    border-radius: 0.75rem;
    overflow-x: auto;
    margin: 2rem 0;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.wysiwyg-content pre code {
    background: transparent;
    padding: 0;
    color: #e2e8f0;
}

/* Tables */
.wysiwyg-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 2rem 0;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 0.5rem;
    overflow: hidden;
}

.wysiwyg-content th,
.wysiwyg-content td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.wysiwyg-content th {
    background: rgba(147, 51, 234, 0.2);
    font-weight: bold;
    color: rgb(var(--cosmic-text));
}

.wysiwyg-content tr:hover {
    background: rgba(255, 255, 255, 0.05);
}

/* Images in content */
.wysiwyg-content img {
    max-width: 100%;
    height: auto;
    border-radius: 0.5rem;
    margin: 2rem 0;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

/* Horizontal rule */
.wysiwyg-content hr {
    border: none;
    height: 2px;
    background: linear-gradient(to right, transparent, rgb(var(--cosmic-primary)), transparent);
    margin: 3rem 0;
}

/* Text alignment classes */
.wysiwyg-content .ql-align-center {
    text-align: center;
}

.wysiwyg-content .ql-align-right {
    text-align: right;
}

.wysiwyg-content .ql-align-left {
    text-align: left;
}

.wysiwyg-content .ql-align-justify {
    text-align: justify;
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
    overflow: hidden;
}

.related-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 15px 30px rgba(var(--cosmic-primary), 0.3);
}

.related-image-container {
    position: relative;
    overflow: hidden;
}

.related-featured-image {
    transition: transform 0.3s ease;
}

.related-card:hover .related-featured-image {
    transform: scale(1.05);
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
    .post-hero-image {
        height: 250px !important;
    }
    
    .post-title {
        font-size: 2rem;
        line-height: 1.2;
    }

    .post-meta-row {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
        padding: 1rem;
    }
    
    .author-info,
    .post-date,
    .post-category {
        justify-content: center;
    }
    
    .content-wrapper {
        padding: 1.5rem;
    }

    .wysiwyg-content {
        font-size: 1rem;
    }
    
    .wysiwyg-content h1 { font-size: 1.75rem; }
    .wysiwyg-content h2 { font-size: 1.5rem; }
    .wysiwyg-content h3 { font-size: 1.25rem; }
    
    .wysiwyg-content pre {
        padding: 1rem;
        overflow-x: auto;
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