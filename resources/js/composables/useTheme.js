import { ref, computed, watch, readonly } from 'vue'

// Theme state
const isDarkMode = ref(false)
const isInitialized = ref(false)

// Initialize theme from localStorage or system preference
export function initializeTheme() {
    if (isInitialized.value) return
    
    const saved = localStorage.getItem('cosmic-theme')
    if (saved) {
        isDarkMode.value = saved === 'dark'
    } else {
        // Check system preference
        isDarkMode.value = window.matchMedia('(prefers-color-scheme: dark)').matches
    }
    
    applyTheme()
    isInitialized.value = true
}

// Apply theme to document
function applyTheme() {
    const html = document.documentElement
    
    if (isDarkMode.value) {
        html.classList.add('dark')
        html.style.colorScheme = 'dark'
    } else {
        html.classList.remove('dark')
        html.style.colorScheme = 'light'
    }
}

// Watch for theme changes and persist
watch(isDarkMode, (newValue) => {
    localStorage.setItem('cosmic-theme', newValue ? 'dark' : 'light')
    applyTheme()
})

// Theme composable
export function useTheme() {
    const toggleTheme = () => {
        isDarkMode.value = !isDarkMode.value
    }
    
    const setTheme = (theme) => {
        isDarkMode.value = theme === 'dark'
    }
    
    const currentTheme = computed(() => isDarkMode.value ? 'dark' : 'light')
    
    return {
        isDarkMode: readonly(isDarkMode),
        currentTheme,
        toggleTheme,
        setTheme,
        initializeTheme
    }
}

// Auto-initialize on import
if (typeof window !== 'undefined') {
    initializeTheme()
}