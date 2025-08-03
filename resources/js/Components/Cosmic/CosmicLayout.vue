<template>
    <v-app>
        <div class="cosmic-layout" :class="layoutClasses">
            <CosmicBackground :variant="backgroundVariant" :animated="animated">
            <!-- Header Section -->
            <header v-if="showHeader" class="cosmic-header">
                <div class="cosmic-header-content">
                    <slot name="header">
                        <div class="cosmic-header-default">
                            <Link 
                                :href="route('home')" 
                                class="cosmic-brand-link"
                            >
                                <h1 class="cosmic-title">Shiny Starborne</h1>
                            </Link>
                            <div class="cosmic-header-actions">
                                <ThemeSwitcher />
                            </div>
                        </div>
                    </slot>
                </div>
            </header>

            <!-- Main Content -->
            <main class="cosmic-main">
                <div class="cosmic-container">
                    <slot></slot>
                </div>
            </main>

            <!-- Footer Section -->
            <footer v-if="showFooter" class="cosmic-footer">
                <div class="cosmic-footer-content">
                    <slot name="footer">
                        <div class="cosmic-footer-default">
                            <p class="cosmic-footer-text">
                                {{ footerText }}
                            </p>
                        </div>
                    </slot>
                </div>
            </footer>
        </CosmicBackground>
    </div>
    </v-app>
</template>

<script setup>
import { computed } from "vue"
import { Link } from '@inertiajs/vue3'
import { useTheme } from "@/composables/useTheme"
import CosmicBackground from "./CosmicBackground.vue"
import ThemeSwitcher from "./ThemeSwitcher.vue"

// Props
const props = defineProps({
    title: {
        type: String,
        default: "Shiny Starborne"
    },
    footerText: {
        type: String,
        default: "Powered by cosmic energy ✨"
    },
    showHeader: {
        type: Boolean,
        default: true
    },
    showFooter: {
        type: Boolean,
        default: true
    },
    backgroundVariant: {
        type: String,
        default: "default"
    },
    animated: {
        type: Boolean,
        default: true
    },
    variant: {
        type: String,
        default: "default", // default, hero, minimal
        validator: (value) => ["default", "hero", "minimal"].includes(value)
    }
})

// Theme composable
const { isDarkMode } = useTheme()

// Computed classes
const layoutClasses = computed(() => [
    `cosmic-layout-${props.variant}`,
    {
        "cosmic-layout-dark": isDarkMode.value,
        "cosmic-layout-light": !isDarkMode.value
    }
])
</script>

<style scoped>
.cosmic-layout {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

/* Header Styles */
.cosmic-header {
    position: relative;
    z-index: 20;
    backdrop-filter: blur(10px);
    border-bottom: 1px solid rgb(var(--cosmic-border));
}

.cosmic-header-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 1rem 2rem;
}

.cosmic-header-default {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.cosmic-brand-link {
    text-decoration: none;
    display: inline-block;
    transition: transform 0.3s ease;
}

.cosmic-brand-link:hover {
    transform: scale(1.05);
}

.cosmic-title {
    font-size: 1.875rem;
    font-weight: 700;
    background: linear-gradient(135deg, rgb(var(--cosmic-primary)), rgb(var(--cosmic-accent)));
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-size: 200% 200%;
    animation: cosmic-text-glow 3s ease-in-out infinite;
    margin: 0;
}

.cosmic-layout-dark .cosmic-title {
    filter: brightness(1.2);
}

.cosmic-header-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

/* Main Content */
.cosmic-main {
    flex: 1;
    position: relative;
    z-index: 10;
}

.cosmic-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

/* Footer Styles */
.cosmic-footer {
    position: relative;
    z-index: 20;
    backdrop-filter: blur(10px);
    border-top: 1px solid rgb(var(--cosmic-border));
    margin-top: auto;
}

.cosmic-footer-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 1rem 2rem;
}

.cosmic-footer-default {
    text-align: center;
}

.cosmic-footer-text {
    color: rgb(var(--cosmic-text-secondary));
    font-size: 0.875rem;
}

/* Removed - handled by CSS variables */

/* Layout Variants */
.cosmic-layout-hero .cosmic-header,
.cosmic-layout-hero .cosmic-footer {
    background: rgb(var(--cosmic-bg) / 0.5);
}

.cosmic-layout-minimal .cosmic-header,
.cosmic-layout-minimal .cosmic-footer {
    backdrop-filter: none;
    border: none;
    background: transparent;
}

/* Animations */
@keyframes cosmic-text-glow {
    0%, 100% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
}

/* Glass morphism effects */
.cosmic-header,
.cosmic-footer {
    background: rgb(var(--cosmic-bg) / 0.8);
}

/* Responsive Design */
@media (max-width: 768px) {
    .cosmic-header-content,
    .cosmic-footer-content,
    .cosmic-container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .cosmic-title {
        font-size: 1.5rem;
    }
    
    .cosmic-header-default {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
}

@media (max-width: 480px) {
    .cosmic-title {
        font-size: 1.25rem;
    }
}
</style>
