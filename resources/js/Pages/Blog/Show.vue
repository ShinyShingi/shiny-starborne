<template>
    <Head :title="post.title" />
    
    <CosmicLayout 
        :title="post.title" 
        background-variant="hero"
    >
        <article class="post-container">
            <!-- Post Header -->
            <header class="post-header">
                <div class="post-meta-top">
                    <Link 
                        :href="route('blog.category', post.category.slug)"
                        class="post-category-badge"
                    >
                        <i class="pi pi-tag"></i>
                        {{ post.category.name }}
                    </Link>
                    <div class="post-date">
                        <i class="pi pi-calendar"></i>
                        {{ formatDate(post.published_at) }}
                    </div>
                </div>
                
                <h1 class="post-title">{{ post.title }}</h1>
                
                <div class="post-author-info">
                    <div class="author-avatar">
                        <i class="pi pi-user"></i>
                    </div>
                    <div class="author-details">
                        <div class="author-name">{{ post.user.name }}</div>
                        <div class="author-role">Cosmic Explorer</div>
                    </div>
                </div>
            </header>

            <!-- Post Content -->
            <div class="post-content">
                <Card class="content-card">
                    <template #content>
                        <div class="prose" v-html="post.content"></div>
                    </template>
                </Card>
            </div>

            <!-- Share Section -->
            <div class="post-share">
                <h3 class="share-title">Share this cosmic insight</h3>
                <div class="share-buttons">
                    <Button 
                        icon="pi pi-link"
                        severity="secondary"
                        outlined
                        rounded
                        v-tooltip="'Copy link'"
                        @click="copyLink"
                    />
                    <Button 
                        icon="pi pi-twitter"
                        severity="secondary"
                        outlined
                        rounded
                        v-tooltip="'Share on Twitter'"
                        @click="shareOnTwitter"
                    />
                    <Button 
                        icon="pi pi-facebook"
                        severity="secondary"
                        outlined
                        rounded
                        v-tooltip="'Share on Facebook'"
                        @click="shareOnFacebook"
                    />
                </div>
            </div>

            <!-- Navigation -->
            <div class="post-navigation">
                <Link 
                    :href="route('blog.index')"
                    class="nav-link nav-back"
                >
                    <i class="pi pi-arrow-left"></i>
                    Back to Blog
                </Link>
                <Link 
                    :href="route('blog.category', post.category.slug)"
                    class="nav-link nav-category"
                >
                    More in {{ post.category.name }}
                    <i class="pi pi-arrow-right"></i>
                </Link>
            </div>

            <!-- Related Posts -->
            <section v-if="relatedPosts.length > 0" class="related-posts">
                <h2 class="related-title">
                    Continue Your 
                    <span class="cosmic-gradient-text">Cosmic Journey</span>
                </h2>
                <div class="related-grid">
                    <Card 
                        v-for="relatedPost in relatedPosts" 
                        :key="relatedPost.id"
                        class="related-card"
                    >
                        <template #title>
                            <Link 
                                :href="route('blog.show', relatedPost.slug)"
                                class="related-card-title"
                            >
                                {{ relatedPost.title }}
                            </Link>
                        </template>
                        <template #content>
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
                                    <i class="pi pi-arrow-right"></i>
                                </Link>
                            </div>
                        </template>
                    </Card>
                </div>
            </section>
        </article>
    </CosmicLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { useToast } from 'primevue/usetoast'
import CosmicLayout from '@/Components/Cosmic/CosmicLayout.vue'
import Card from 'primevue/card'
import Button from 'primevue/button'
import Tooltip from 'primevue/tooltip'

const props = defineProps({
    post: Object,
    relatedPosts: Array
})

const toast = useToast()

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
    toast.add({
        severity: 'success',
        summary: 'Link Copied',
        detail: 'Post link copied to clipboard',
        life: 3000
    })
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
.post-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 2rem;
}

/* Post Header */
.post-header {
    text-align: center;
    margin-bottom: 3rem;
}

.post-meta-top {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 2rem;
    margin-bottom: 1.5rem;
}

.post-category-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: linear-gradient(135deg, var(--p-primary-color), var(--p-primary-600));
    border-radius: 2rem;
    color: white;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.post-category-badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(147, 51, 234, 0.4);
}

.post-date {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.9rem;
}

.post-title {
    font-size: 3rem;
    font-weight: 800;
    color: white;
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
    border-radius: 50%;
    background: linear-gradient(135deg, var(--p-primary-color), var(--p-accent-color));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.author-details {
    text-align: left;
}

.author-name {
    font-weight: 600;
    color: white;
    font-size: 1.1rem;
}

.author-role {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.9rem;
}

/* Post Content */
.content-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 1rem;
    margin-bottom: 3rem;
}

.prose {
    color: rgba(255, 255, 255, 0.9);
    line-height: 1.8;
    font-size: 1.1rem;
}

.prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
    color: white;
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
    color: var(--p-primary-color);
    text-decoration: underline;
}

.prose a:hover {
    color: var(--p-accent-color);
}

.prose blockquote {
    border-left: 4px solid var(--p-primary-color);
    padding-left: 1.5rem;
    margin: 2rem 0;
    font-style: italic;
    color: rgba(255, 255, 255, 0.8);
}

.prose code {
    background: rgba(0, 0, 0, 0.3);
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-family: monospace;
    font-size: 0.9em;
}

.prose pre {
    background: rgba(0, 0, 0, 0.4);
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
.post-share {
    text-align: center;
    padding: 2rem;
    margin-bottom: 2rem;
}

.share-title {
    font-size: 1.25rem;
    color: white;
    margin-bottom: 1rem;
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
    margin-bottom: 3rem;
    padding: 0 1rem;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--p-primary-color);
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.nav-link:hover {
    color: var(--p-accent-color);
}

.nav-back:hover {
    gap: 0.75rem;
}

.nav-category:hover {
    gap: 0.75rem;
}

/* Related Posts */
.related-posts {
    margin-top: 4rem;
}

.related-title {
    text-align: center;
    font-size: 2rem;
    font-weight: 700;
    color: white;
    margin-bottom: 2rem;
}

.cosmic-gradient-text {
    background: linear-gradient(135deg, var(--p-primary-color), var(--p-accent-color));
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}

.related-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.related-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 1rem;
    transition: all 0.3s ease;
}

.related-card:hover {
    transform: translateY(-4px);
    border-color: var(--p-primary-color);
    box-shadow: 0 15px 30px rgba(147, 51, 234, 0.3);
}

.related-card-title {
    color: white;
    text-decoration: none;
    font-size: 1.25rem;
    font-weight: 600;
    display: block;
    margin-bottom: 0.5rem;
    transition: color 0.3s ease;
}

.related-card-title:hover {
    color: var(--p-primary-color);
}

.related-excerpt {
    color: rgba(255, 255, 255, 0.8);
    line-height: 1.6;
    margin-bottom: 1rem;
}

.related-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.related-date {
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.85rem;
}

.related-read-more {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--p-primary-color);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.related-read-more:hover {
    color: var(--p-accent-color);
    gap: 0.75rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .post-container {
        padding: 1rem;
    }

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

    .related-grid {
        grid-template-columns: 1fr;
    }

    .share-buttons {
        flex-wrap: wrap;
    }
}

/* Dark mode adjustments */
:global(.dark) .content-card {
    background: rgba(0, 0, 0, 0.3);
    border-color: rgba(255, 255, 255, 0.1);
}

:global(.dark) .related-card {
    background: rgba(0, 0, 0, 0.3);
    border-color: rgba(255, 255, 255, 0.1);
}

:global(.dark) .prose {
    color: rgba(255, 255, 255, 0.85);
}
</style>