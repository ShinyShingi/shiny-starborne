<template>
    <Head title="Welcome to Shiny Starborne" />
    
    <CosmicLayout 
        title="Shiny Starborne" 
        background-variant="hero"
        variant="hero"
        :animated="true"
    >
        <!-- Hero Section -->
        <div class="cosmic-hero">
            <div class="cosmic-hero-content">
                <h1 class="cosmic-hero-title">
                    Welcome to the
                    <span class="cosmic-gradient-text">Cosmic Era</span>
                </h1>
                <p class="cosmic-hero-subtitle">
                    Experience the power of Laravel 12 with Vue 3, Inertia.js, and PrimeVue
                    in a stunning cosmic interface that adapts to your preference.
                </p>
                
                <!-- Action Buttons -->
                <div class="cosmic-hero-actions">
                    <Button 
                        v-if="$page.props.auth.user"
                        @click="() => $inertia.visit(route(dashboard))"
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
                            severity="secondary"
                            outlined
                            class="cosmic-btn-secondary"
                        />
                    </template>
                    <Button 
                        @click="() => $inertia.visit(route('blog.index'))"
                        label="Explore Blog" 
                        icon="pi pi-book" 
                        size="large"
                        severity="info"
                        outlined
                        class="cosmic-btn-secondary"
                    />
                </div>
            </div>
        </div>

        <!-- Features Grid -->
        <div class="cosmic-features">
            <div class="cosmic-features-grid">
                <!-- Laravel Feature -->
                <Card class="cosmic-feature-card">
                    <template #header>
                        <div class="cosmic-feature-icon cosmic-feature-icon-laravel">
                            <i class="pi pi-code" style="font-size: 2rem"></i>
                        </div>
                    </template>
                    <template #title>
                        <h3 class="cosmic-feature-title">Laravel 12</h3>
                    </template>
                    <template #content>
                        <p class="cosmic-feature-description">
                            Built on the latest Laravel framework with all the modern features 
                            and performance improvements you need for stellar applications.
                        </p>
                    </template>
                </Card>

                <!-- Vue Feature -->
                <Card class="cosmic-feature-card">
                    <template #header>
                        <div class="cosmic-feature-icon cosmic-feature-icon-vue">
                            <i class="pi pi-sparkles" style="font-size: 2rem"></i>
                        </div>
                    </template>
                    <template #title>
                        <h3 class="cosmic-feature-title">Vue 3 + Inertia</h3>
                    </template>
                    <template #content>
                        <p class="cosmic-feature-description">
                            Reactive frontend powered by Vue 3 and Inertia.js for 
                            seamless single-page application experience.
                        </p>
                    </template>
                </Card>

                <!-- PrimeVue Feature -->
                <Card class="cosmic-feature-card">
                    <template #header>
                        <div class="cosmic-feature-icon cosmic-feature-icon-prime">
                            <i class="pi pi-palette" style="font-size: 2rem"></i>
                        </div>
                    </template>
                    <template #title>
                        <h3 class="cosmic-feature-title">Cosmic PrimeVue</h3>
                    </template>
                    <template #content>
                        <p class="cosmic-feature-description">
                            Beautiful UI components with custom cosmic theming, 
                            supporting both light and dark modes with smooth transitions.
                        </p>
                    </template>
                </Card>

                <!-- Database Feature -->
                <Card class="cosmic-feature-card">
                    <template #header>
                        <div class="cosmic-feature-icon cosmic-feature-icon-data">
                            <i class="pi pi-database" style="font-size: 2rem"></i>
                        </div>
                    </template>
                    <template #title>
                        <h3 class="cosmic-feature-title">Full Stack Ready</h3>
                    </template>
                    <template #content>
                        <p class="cosmic-feature-description">
                            Complete with MySQL database, Redis caching, 
                            and Docker containerization for development and production.
                        </p>
                    </template>
                </Card>
            </div>
        </div>

        <!-- Version Info -->
        <div class="cosmic-version-info">
            <Badge 
                :value="`Laravel v${laravelVersion}`" 
                severity="secondary" 
                class="cosmic-badge"
            />
            <Badge 
                :value="`PHP v${phpVersion}`" 
                severity="secondary" 
                class="cosmic-badge"
            />
        </div>

        <!-- Theme Showcase -->
        <template #footer>
            <div class="cosmic-footer-showcase">
                <p class="cosmic-footer-text">
                    ✨ Powered by cosmic energy and modern web technologies
                </p>
                <div class="cosmic-theme-indicator">
                    <span class="cosmic-theme-label">Current theme:</span>
                    <Badge 
                        :value="currentTheme" 
                        :severity="isDarkMode ? contrast : info"
                        class="cosmic-theme-badge"
                    />
                </div>
            </div>
        </template>
    </CosmicLayout>
</template>

<script setup>
import { Head } from "@inertiajs/vue3"
import { useTheme } from "@/composables/useTheme"
import CosmicLayout from "@/Components/Cosmic/CosmicLayout.vue"
import Button from "primevue/button"
import Card from "primevue/card"
import Badge from "primevue/badge"

// Props
defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
})

// Theme composable
const { isDarkMode, currentTheme } = useTheme()
</script>

<style scoped>
/* Hero Section */
.cosmic-hero {
    padding: 4rem 0 6rem;
    text-align: center;
}

.cosmic-hero-content {
    max-width: 800px;
    margin: 0 auto;
}

.cosmic-hero-title {
    font-size: 3.5rem;
    font-weight: 900;
    line-height: 1.1;
    margin-bottom: 1.5rem;
    color: white;
    text-shadow: 0 0 30px rgba(168, 85, 247, 0.5);
}

.cosmic-gradient-text {
    background: linear-gradient(135deg, #a855f7, #3b82f6, #ec4899);
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-size: 200% 200%;
    animation: cosmic-text-flow 4s ease-in-out infinite;
}

.cosmic-hero-subtitle {
    font-size: 1.25rem;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 3rem;
    line-height: 1.6;
}

.cosmic-hero-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

/* Feature Cards */
.cosmic-features {
    padding: 4rem 0;
}

.cosmic-features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.cosmic-feature-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 1rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.cosmic-feature-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(168, 85, 247, 0.2);
    border-color: rgba(168, 85, 247, 0.4);
}

.cosmic-feature-icon {
    width: 4rem;
    height: 4rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    color: white;
    position: relative;
    overflow: hidden;
}

.cosmic-feature-icon::before {
    content: "";
    position: absolute;
    inset: 0;
    border-radius: inherit;
    background: linear-gradient(45deg, #a855f7, #3b82f6, #ec4899);
    background-size: 200% 200%;
    animation: cosmic-icon-glow 3s ease infinite;
    z-index: -1;
}

.cosmic-feature-icon-laravel {
    background: linear-gradient(135deg, #a855f7, #7c3aed);
}

.cosmic-feature-icon-vue {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
}

.cosmic-feature-icon-prime {
    background: linear-gradient(135deg, #ec4899, #be185d);
}

.cosmic-feature-icon-data {
    background: linear-gradient(135deg, #a855f7, #3b82f6);
}

.cosmic-feature-title {
    color: white;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    text-align: center;
}

.cosmic-feature-description {
    color: rgba(255, 255, 255, 0.8);
    line-height: 1.6;
    text-align: center;
}

/* Version Info */
.cosmic-version-info {
    display: flex;
    gap: 1rem;
    justify-content: center;
    padding: 2rem 0;
}

.cosmic-badge {
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
}

/* Footer Showcase */
.cosmic-footer-showcase {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    text-align: center;
}

.cosmic-theme-indicator {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
}

.cosmic-theme-label {
    color: rgba(255, 255, 255, 0.7);
}

.cosmic-theme-badge {
    text-transform: capitalize;
}

/* Button Styling */
.cosmic-btn-primary {
    background: linear-gradient(135deg, #a855f7, #7c3aed) \!important;
    border: none \!important;
    box-shadow: 0 4px 15px rgba(168, 85, 247, 0.4) \!important;
    transition: all 0.3s ease \!important;
}

.cosmic-btn-primary:hover {
    transform: translateY(-2px) \!important;
    box-shadow: 0 8px 25px rgba(168, 85, 247, 0.6) \!important;
}

.cosmic-btn-secondary {
    border-color: rgba(255, 255, 255, 0.3) \!important;
    color: white \!important;
    backdrop-filter: blur(10px);
}

.cosmic-btn-secondary:hover {
    background: rgba(255, 255, 255, 0.1) \!important;
    border-color: rgba(255, 255, 255, 0.5) \!important;
    transform: translateY(-2px) \!important;
}

/* Animations */
@keyframes cosmic-text-flow {
    0%, 100% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
}

@keyframes cosmic-icon-glow {
    0%, 100% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .cosmic-hero-title {
        font-size: 2.5rem;
    }
    
    .cosmic-hero-subtitle {
        font-size: 1.125rem;
    }
    
    .cosmic-hero-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .cosmic-features-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .cosmic-version-info {
        flex-direction: column;
        align-items: center;
    }
}

@media (max-width: 480px) {
    .cosmic-hero {
        padding: 2rem 0 4rem;
    }
    
    .cosmic-hero-title {
        font-size: 2rem;
    }
    
    .cosmic-features {
        padding: 2rem 0;
    }
}

/* Dark mode specific adjustments */
:global(.dark) .cosmic-feature-card {
    background: rgba(0, 0, 0, 0.3);
    border-color: rgba(255, 255, 255, 0.1);
}

:global(.dark) .cosmic-feature-card:hover {
    border-color: rgba(168, 85, 247, 0.5);
}
</style>
