<template>
    <div class="theme-switcher">
        <Button
            @click="toggleTheme"
            :icon="isDarkMode ? 'pi pi-sun' : 'pi pi-moon'"
            severity="secondary"
            text
            rounded
            :class="switcherClasses"
            v-tooltip.bottom="tooltipText"
            aria-label="Toggle theme"
        >
            <template #default>
                <span class="sr-only">{{ tooltipText }}</span>
            </template>
        </Button>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import { useTheme } from '@/composables/useTheme'
import Button from 'primevue/button'
import Tooltip from 'primevue/tooltip'

// Theme composable
const { isDarkMode, toggleTheme } = useTheme()

// Computed properties
const tooltipText = computed(() => 
    isDarkMode.value ? 'Switch to light mode' : 'Switch to dark mode'
)

const switcherClasses = computed(() => [
    'theme-switcher-btn',
    'cosmic-glow',
    {
        'theme-switcher-dark': isDarkMode.value,
        'theme-switcher-light': !isDarkMode.value
    }
])
</script>

<style scoped>
.theme-switcher {
    @apply relative;
}

.theme-switcher-btn {
    @apply transition-all duration-300 ease-in-out;
    @apply border border-transparent;
    @apply backdrop-blur-sm;
    position: relative;
    overflow: hidden;
}

.theme-switcher-btn::before {
    content: '';
    @apply absolute inset-0 opacity-0 transition-opacity duration-300;
    background: linear-gradient(45deg, #9333ea, #3b82f6, #ec4899);
    background-size: 200% 200%;
    animation: cosmic-gradient 3s ease infinite;
    border-radius: inherit;
    z-index: -1;
}

.theme-switcher-btn:hover::before {
    @apply opacity-20;
}

.theme-switcher-light {
    @apply bg-white/80 text-purple-700 shadow-lg;
    @apply hover:bg-white/90 hover:text-purple-800;
    @apply hover:shadow-purple-200;
}

.theme-switcher-dark {
    @apply bg-gray-800/80 text-purple-300 shadow-lg;
    @apply hover:bg-gray-700/90 hover:text-purple-200;
    @apply hover:shadow-purple-500/20;
}

.cosmic-glow {
    @apply hover:shadow-xl;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.cosmic-glow:hover {
    transform: translateY(-1px);
    box-shadow: 
        0 10px 25px -3px rgba(147, 51, 234, 0.1),
        0 4px 6px -2px rgba(147, 51, 234, 0.05),
        0 0 20px rgba(147, 51, 234, 0.1);
}

.theme-switcher-dark.cosmic-glow:hover {
    box-shadow: 
        0 10px 25px -3px rgba(147, 51, 234, 0.3),
        0 4px 6px -2px rgba(147, 51, 234, 0.2),
        0 0 20px rgba(147, 51, 234, 0.2);
}

@keyframes cosmic-gradient {
    0%, 100% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
}

.sr-only {
    @apply absolute w-px h-px p-0 -m-px overflow-hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}
</style>
