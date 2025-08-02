<template>
    <div class="cosmic-layout" :class="layoutClasses">
        <Toast position="top-right" />
        <CosmicBackground :variant="backgroundVariant" :animated="animated">
            <!-- Header Section -->
            <header v-if="showHeader" class="cosmic-header">
                <div class="cosmic-header-content">
                    <slot name="header">
                        <div class="cosmic-header-default">
                            <h1 class="cosmic-title">{{ title }}</h1>
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
</template>

<script setup>
import { computed } from "vue"
import { useTheme } from "@/composables/useTheme"
import CosmicBackground from "./CosmicBackground.vue"
import ThemeSwitcher from "./ThemeSwitcher.vue"
import Toast from 'primevue/toast'

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
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
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

.cosmic-title {
    font-size: 1.875rem;
    font-weight: 700;
    background: linear-gradient(135deg, #a855f7, #3b82f6, #ec4899);
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-size: 200% 200%;
    animation: cosmic-text-glow 3s ease-in-out infinite;
}

.cosmic-layout-dark .cosmic-title {
    color: white;
    -webkit-text-fill-color: white;
    text-shadow: 0 0 20px rgba(168, 85, 247, 0.5);
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
    border-top: 1px solid rgba(255, 255, 255, 0.1);
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
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.875rem;
}

.cosmic-layout-light .cosmic-footer-text {
    color: rgba(0, 0, 0, 0.6);
}

/* Layout Variants */
.cosmic-layout-hero .cosmic-header {
    background: rgba(0, 0, 0, 0.1);
}

.cosmic-layout-hero .cosmic-footer {
    background: rgba(0, 0, 0, 0.1);
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
.cosmic-layout-dark .cosmic-header,
.cosmic-layout-dark .cosmic-footer {
    background: rgba(0, 0, 0, 0.2);
    border-color: rgba(255, 255, 255, 0.1);
}

.cosmic-layout-light .cosmic-header,
.cosmic-layout-light .cosmic-footer {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(0, 0, 0, 0.1);
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
