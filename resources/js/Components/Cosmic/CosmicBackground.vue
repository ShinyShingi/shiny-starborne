<template>
    <div class="cosmic-background" :class="backgroundClasses">
        <!-- Animated gradient overlay -->
        <div class="cosmic-gradient-overlay"></div>
        
        <!-- Floating particles -->
        <div class="cosmic-particles">
            <div 
                v-for="n in particleCount" 
                :key="n" 
                class="cosmic-particle"
                :style="getParticleStyle(n)"
            ></div>
        </div>
        
        <!-- Content slot -->
        <div class="cosmic-content">
            <slot></slot>
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from "vue"
import { useTheme } from "@/composables/useTheme"

// Props
const props = defineProps({
    variant: {
        type: String,
        default: "default", // default, hero, card, minimal
        validator: (value) => ["default", "hero", "card", "minimal"].includes(value)
    },
    particleCount: {
        type: Number,
        default: 20
    },
    animated: {
        type: Boolean,
        default: true
    }
})

// Theme composable
const { isDarkMode } = useTheme()

// Computed classes
const backgroundClasses = computed(() => [
    "cosmic-bg",
    `cosmic-bg-${props.variant}`,
    {
        "cosmic-bg-dark": isDarkMode.value,
        "cosmic-bg-light": !isDarkMode.value,
        "cosmic-animated": props.animated
    }
])

// Particle positioning
const getParticleStyle = (index) => {
    const delay = Math.random() * 20
    const duration = 15 + Math.random() * 10
    const size = 2 + Math.random() * 4
    
    return {
        left: `${Math.random() * 100}%`,
        top: `${Math.random() * 100}%`,
        width: `${size}px`,
        height: `${size}px`,
        animationDelay: `${delay}s`,
        animationDuration: `${duration}s`
    }
}
</script>

<style scoped>
.cosmic-background {
    position: relative;
    min-height: 100vh;
    overflow: hidden;
}

/* Base background variants */
.cosmic-bg-default {
    background: linear-gradient(
        135deg,
        rgb(168, 85, 247) 0%,
        rgb(59, 130, 246) 50%,
        rgb(236, 72, 153) 100%
    );
}

.cosmic-bg-hero {
    background: radial-gradient(
        ellipse at top,
        rgb(168, 85, 247) 0%,
        rgb(124, 58, 237) 25%,
        rgb(59, 130, 246) 50%,
        rgb(29, 78, 216) 75%,
        rgb(15, 23, 42) 100%
    );
}

.cosmic-bg-card {
    background: linear-gradient(
        45deg,
        rgba(168, 85, 247, 0.1) 0%,
        rgba(59, 130, 246, 0.1) 50%,
        rgba(236, 72, 153, 0.1) 100%
    );
}

.cosmic-bg-minimal {
    background: linear-gradient(
        to bottom,
        rgba(168, 85, 247, 0.05) 0%,
        rgba(59, 130, 246, 0.05) 100%
    );
}

/* Light mode adjustments */
.cosmic-bg-light.cosmic-bg-default {
    background: linear-gradient(
        135deg,
        rgb(243, 232, 255) 0%,
        rgb(219, 234, 254) 50%,
        rgb(252, 231, 243) 100%
    );
}

.cosmic-bg-light.cosmic-bg-hero {
    background: radial-gradient(
        ellipse at top,
        rgb(243, 232, 255) 0%,
        rgb(237, 233, 254) 25%,
        rgb(219, 234, 254) 50%,
        rgb(191, 219, 254) 75%,
        rgb(255, 255, 255) 100%
    );
}

/* Gradient overlay */
.cosmic-gradient-overlay {
    position: absolute;
    inset: 0;
    opacity: 0.3;
    background: linear-gradient(
        45deg,
        transparent 0%,
        rgba(168, 85, 247, 0.1) 25%,
        rgba(59, 130, 246, 0.1) 50%,
        rgba(236, 72, 153, 0.1) 75%,
        transparent 100%
    );
    background-size: 200% 200%;
}

.cosmic-animated .cosmic-gradient-overlay {
    animation: cosmic-flow 20s ease-in-out infinite;
}

/* Particles */
.cosmic-particles {
    position: absolute;
    inset: 0;
    pointer-events: none;
    overflow: hidden;
}

.cosmic-particle {
    position: absolute;
    border-radius: 50%;
    opacity: 0.2;
    background: radial-gradient(
        circle,
        rgba(255, 255, 255, 0.8) 0%,
        rgba(168, 85, 247, 0.4) 50%,
        transparent 100%
    );
}

.cosmic-animated .cosmic-particle {
    animation: cosmic-float linear infinite;
}

.cosmic-bg-dark .cosmic-particle {
    background: radial-gradient(
        circle,
        rgba(255, 255, 255, 0.6) 0%,
        rgba(168, 85, 247, 0.3) 50%,
        transparent 100%
    );
}

/* Content */
.cosmic-content {
    position: relative;
    z-index: 10;
}

/* Animations */
@keyframes cosmic-flow {
    0%, 100% {
        background-position: 0% 50%;
        transform: rotate(0deg);
    }
    25% {
        background-position: 100% 0%;
        transform: rotate(1deg);
    }
    50% {
        background-position: 100% 100%;
        transform: rotate(0deg);
    }
    75% {
        background-position: 0% 100%;
        transform: rotate(-1deg);
    }
}

@keyframes cosmic-float {
    0% {
        transform: translateY(100vh) rotate(0deg) scale(0);
        opacity: 0;
    }
    10% {
        opacity: 0.2;
        transform: scale(1);
    }
    90% {
        opacity: 0.2;
    }
    100% {
        transform: translateY(-100vh) rotate(360deg) scale(0);
        opacity: 0;
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .cosmic-particles {
        display: none;
    }
}

@media (prefers-reduced-motion: reduce) {
    .cosmic-gradient-overlay,
    .cosmic-particle {
        animation: none;
    }
}
</style>
