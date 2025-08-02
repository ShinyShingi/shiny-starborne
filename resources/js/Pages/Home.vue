<template>
    <Head title="Home - Shiny Starborne" />
    
    <CosmicLayout 
        title="Shiny Starborne" 
        background-variant="hero"
        :show-header="true"
    >
        <!-- Hero Section -->
        <section class="home-hero">
            <div class="hero-content">
                <h1 class="hero-title">
                    Welcome to the
                    <span class="cosmic-gradient-text">Cosmic Era</span>
                </h1>
                <p class="hero-subtitle">
                    Explore our stellar content, discover cosmic insights, and join our interstellar community
                </p>
                <div class="hero-actions">
                    <Button 
                        v-if="$page.props.auth.user"
                        @click="() => $inertia.visit(route('dashboard'))"
                        label="Enter Dashboard" 
                        icon="pi pi-rocket" 
                        size="large"
                        class="cosmic-btn-primary"
                    />
                    <template v-else>
                        <Button 
                            v-if="canLogin"
                            @click="() => $inertia.visit(route('login'))"
                            label="Sign In" 
                            icon="pi pi-sign-in" 
                            size="large"
                            class="cosmic-btn-primary"
                        />
                        <Button 
                            v-if="canRegister"
                            @click="() => $inertia.visit(route('register'))"
                            label="Join the Cosmos" 
                            icon="pi pi-user-plus" 
                            size="large"
                            outlined
                            class="cosmic-btn-secondary"
                        />
                    </template>
                </div>
            </div>
            <div class="hero-stats">
                <div class="stat-card">
                    <div class="stat-value">{{ totalPosts }}</div>
                    <div class="stat-label">Cosmic Articles</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ categories.length }}</div>
                    <div class="stat-label">Constellations</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">∞</div>
                    <div class="stat-label">Possibilities</div>
                </div>
            </div>
        </section>

        <!-- Latest Posts Section -->
        <section class="home-posts">
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
                    <i class="pi pi-arrow-right"></i>
                </Link>
            </div>

            <div class="posts-grid">
                <Card 
                    v-for="post in latestPosts" 
                    :key="post.id"
                    class="post-card"
                >
                    <template #header>
                        <div class="post-header">
                            <Link 
                                :href="route('blog.category', post.category.slug)"
                                class="post-category"
                            >
                                {{ post.category.name }}
                            </Link>
                            <span class="post-date">
                                {{ formatDate(post.published_at) }}
                            </span>
                        </div>
                    </template>
                    
                    <template #title>
                        <Link 
                            :href="route('blog.show', post.slug)"
                            class="post-title"
                        >
                            {{ post.title }}
                        </Link>
                    </template>
                    
                    <template #content>
                        <p class="post-excerpt">{{ truncateText(post.content, 120) }}</p>
                        <div class="post-footer">
                            <span class="post-author">
                                <i class="pi pi-user"></i>
                                {{ post.user.name }}
                            </span>
                            <Link 
                                :href="route('blog.show', post.slug)"
                                class="post-link"
                            >
                                Read More
                                <i class="pi pi-arrow-right"></i>
                            </Link>
                        </div>
                    </template>
                </Card>
            </div>

            <div v-if="latestPosts.length === 0" class="empty-posts">
                <i class="pi pi-inbox empty-icon"></i>
                <p class="empty-text">No cosmic insights available yet</p>
            </div>
        </section>

        <!-- Categories Section -->
        <section class="home-categories">
            <div class="section-header">
                <h2 class="section-title">
                    Explore Our 
                    <span class="cosmic-gradient-text">Constellations</span>
                </h2>
            </div>

            <div class="categories-grid">
                <Link
                    v-for="category in categories"
                    :key="category.id"
                    :href="route('blog.category', category.slug)"
                    class="category-tile"
                >
                    <div class="category-icon">
                        <i class="pi pi-sparkles"></i>
                    </div>
                    <h3 class="category-name">{{ category.name }}</h3>
                    <p class="category-count">
                        {{ category.posts_count }} 
                        {{ category.posts_count === 1 ? 'article' : 'articles' }}
                    </p>
                </Link>
            </div>
        </section>

        <!-- Features Section -->
        <section class="home-features">
            <div class="section-header">
                <h2 class="section-title">
                    Powered by 
                    <span class="cosmic-gradient-text">Stellar Technology</span>
                </h2>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="pi pi-code"></i>
                    </div>
                    <h3 class="feature-title">Laravel 12</h3>
                    <p class="feature-text">
                        Modern PHP framework with elegant syntax and powerful features
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="pi pi-bolt"></i>
                    </div>
                    <h3 class="feature-title">Vue 3 + Inertia</h3>
                    <p class="feature-text">
                        Reactive frontend with seamless server-side integration
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="pi pi-palette"></i>
                    </div>
                    <h3 class="feature-title">Cosmic Theme</h3>
                    <p class="feature-text">
                        Beautiful nebula-inspired design with dark/light modes
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="pi pi-box"></i>
                    </div>
                    <h3 class="feature-title">Docker Ready</h3>
                    <p class="feature-text">
                        Containerized development for consistent deployment
                    </p>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="home-cta">
            <Card class="cta-card">
                <template #content>
                    <div class="cta-content">
                        <h2 class="cta-title">Ready to Start Your Cosmic Journey?</h2>
                        <p class="cta-text">
                            Join our community and explore the infinite possibilities of modern web development
                        </p>
                        <div class="cta-actions">
                            <Button 
                                v-if="!$page.props.auth.user && canRegister"
                                @click="() => $inertia.visit(route('register'))"
                                label="Get Started" 
                                icon="pi pi-rocket" 
                                size="large"
                                class="cosmic-btn-primary"
                            />
                            <Button 
                                @click="() => $inertia.visit(route('blog.index'))"
                                label="Explore Content" 
                                icon="pi pi-book" 
                                size="large"
                                outlined
                                class="cosmic-btn-secondary"
                            />
                        </div>
                    </div>
                </template>
            </Card>
        </section>
    </CosmicLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import CosmicLayout from '@/Components/Cosmic/CosmicLayout.vue'
import Card from 'primevue/card'
import Button from 'primevue/button'

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
.home-hero {
    text-align: center;
    padding: 4rem 2rem 6rem;
    position: relative;
}

.hero-content {
    max-width: 800px;
    margin: 0 auto 3rem;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 800;
    margin-bottom: 1.5rem;
    color: white;
    line-height: 1.1;
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

.hero-subtitle {
    font-size: 1.25rem;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 2.5rem;
    line-height: 1.6;
}

.hero-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.cosmic-btn-primary {
    background: linear-gradient(135deg, var(--p-primary-color), var(--p-primary-600));
    border: none;
    color: white;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(147, 51, 234, 0.3);
}

.cosmic-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(147, 51, 234, 0.4);
}

.cosmic-btn-secondary {
    border: 2px solid var(--p-primary-color);
    color: var(--p-primary-color);
    background: rgba(147, 51, 234, 0.1);
    backdrop-filter: blur(10px);
}

.cosmic-btn-secondary:hover {
    background: var(--p-primary-color);
    color: white;
    transform: translateY(-2px);
}

.hero-stats {
    display: flex;
    justify-content: center;
    gap: 3rem;
    flex-wrap: wrap;
}

.stat-card {
    text-align: center;
}

.stat-value {
    font-size: 3rem;
    font-weight: 800;
    color: var(--p-primary-color);
    margin-bottom: 0.5rem;
}

.stat-label {
    color: rgba(255, 255, 255, 0.7);
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Section Common */
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 3rem;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: white;
    margin: 0;
}

.section-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--p-primary-color);
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.section-link:hover {
    color: var(--p-accent-color);
    gap: 0.75rem;
}

/* Posts Section */
.home-posts {
    padding: 4rem 2rem;
    max-width: 1400px;
    margin: 0 auto;
}

.posts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 2rem;
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
    padding: 1rem;
    background: rgba(0, 0, 0, 0.2);
}

.post-category {
    color: var(--p-primary-color);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.post-date {
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.85rem;
}

.post-title {
    color: white;
    text-decoration: none;
    font-size: 1.25rem;
    font-weight: 700;
    line-height: 1.3;
    display: block;
    margin-bottom: 0.75rem;
    transition: color 0.3s ease;
}

.post-title:hover {
    color: var(--p-primary-color);
}

.post-excerpt {
    color: rgba(255, 255, 255, 0.8);
    line-height: 1.6;
    margin-bottom: 1rem;
}

.post-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.post-author {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.85rem;
}

.post-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--p-primary-color);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.post-link:hover {
    color: var(--p-accent-color);
    gap: 0.75rem;
}

.empty-posts {
    text-align: center;
    padding: 4rem;
}

.empty-icon {
    font-size: 3rem;
    color: rgba(255, 255, 255, 0.3);
}

.empty-text {
    color: rgba(255, 255, 255, 0.6);
    margin-top: 1rem;
}

/* Categories Section */
.home-categories {
    padding: 4rem 2rem;
    max-width: 1400px;
    margin: 0 auto;
    background: rgba(0, 0, 0, 0.2);
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1.5rem;
}

.category-tile {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 1rem;
    padding: 2rem;
    text-align: center;
    text-decoration: none;
    transition: all 0.3s ease;
}

.category-tile:hover {
    transform: translateY(-8px);
    border-color: var(--p-primary-color);
    background: rgba(147, 51, 234, 0.2);
}

.category-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--p-primary-color), var(--p-accent-color));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
    color: white;
}

.category-name {
    color: white;
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.category-count {
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.9rem;
}

/* Features Section */
.home-features {
    padding: 4rem 2rem;
    max-width: 1400px;
    margin: 0 auto;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
}

.feature-card {
    text-align: center;
    padding: 2rem;
}

.feature-icon {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.1);
    border: 2px solid var(--p-primary-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2rem;
    color: var(--p-primary-color);
    transition: all 0.3s ease;
}

.feature-card:hover .feature-icon {
    background: var(--p-primary-color);
    color: white;
    transform: scale(1.1);
}

.feature-title {
    color: white;
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
}

.feature-text {
    color: rgba(255, 255, 255, 0.7);
    line-height: 1.5;
}

/* CTA Section */
.home-cta {
    padding: 4rem 2rem 6rem;
    max-width: 900px;
    margin: 0 auto;
}

.cta-card {
    background: linear-gradient(135deg, rgba(147, 51, 234, 0.2), rgba(236, 72, 153, 0.2));
    border: 1px solid var(--p-primary-color);
    border-radius: 1.5rem;
}

.cta-content {
    text-align: center;
    padding: 3rem;
}

.cta-title {
    font-size: 2rem;
    font-weight: 700;
    color: white;
    margin-bottom: 1rem;
}

.cta-text {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 2rem;
}

.cta-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }

    .hero-subtitle {
        font-size: 1.1rem;
    }

    .hero-stats {
        gap: 2rem;
    }

    .stat-value {
        font-size: 2.5rem;
    }

    .section-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }

    .section-title {
        font-size: 2rem;
    }

    .posts-grid {
        grid-template-columns: 1fr;
    }

    .categories-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    }

    .features-grid {
        grid-template-columns: 1fr;
    }

    .cta-content {
        padding: 2rem;
    }

    .cta-title {
        font-size: 1.5rem;
    }

    .cta-actions {
        flex-direction: column;
        align-items: center;
    }
}

/* Dark mode adjustments */
:global(.dark) .post-card {
    background: rgba(0, 0, 0, 0.3);
    border-color: rgba(255, 255, 255, 0.1);
}

:global(.dark) .category-tile {
    background: rgba(0, 0, 0, 0.3);
    border-color: rgba(255, 255, 255, 0.1);
}

:global(.dark) .feature-icon {
    background: rgba(0, 0, 0, 0.3);
}
</style>