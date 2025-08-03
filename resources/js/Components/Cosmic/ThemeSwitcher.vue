<template>
    <div class="theme-switcher">
        <v-tooltip :text="tooltipText" location="bottom">
            <template v-slot:activator="{ props }">
                <v-btn
                    v-bind="props"
                    @click="toggleTheme"
                    :icon="isDarkMode ? 'mdi-white-balance-sunny' : 'mdi-moon-waning-crescent'"
                    variant="text"
                    :class="switcherClasses"
                    aria-label="Toggle theme"
                />
            </template>
        </v-tooltip>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import { useTheme } from '@/composables/useTheme'
import { useTheme as useVuetifyTheme } from 'vuetify'

// Theme composable
const { isDarkMode, toggleTheme: toggleCosmicTheme } = useTheme()

// Vuetify theme
const vuetifyTheme = useVuetifyTheme()

// Toggle both themes
const toggleTheme = () => {
    toggleCosmicTheme()
    // Update Vuetify theme after cosmic theme changes
    vuetifyTheme.global.name.value = !isDarkMode.value ? 'dark' : 'light'
}

// Sync Vuetify theme with cosmic theme on mount
import { onMounted } from 'vue'
onMounted(() => {
    vuetifyTheme.global.name.value = isDarkMode.value ? 'dark' : 'light'
})

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
</style>