<script setup>
import CosmicLayout from '@/Components/Cosmic/CosmicLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useTheme } from '@/composables/useTheme';
import Card from 'primevue/card';
import Button from 'primevue/button';
import Badge from 'primevue/badge';

const { isDarkMode, currentTheme } = useTheme();
</script>

<template>
    <Head title="Dashboard" />

    <CosmicLayout 
        title="Dashboard" 
        background-variant="default"
        :show-header="true"
    >
        <div class="cosmic-dashboard">
            <div class="cosmic-dashboard-header">
                <h1 class="cosmic-dashboard-title">
                    Welcome to your 
                    <span class="cosmic-gradient-text">Cosmic Dashboard</span>
                </h1>
                <p class="cosmic-dashboard-subtitle">
                    Navigate through the stars and manage your interstellar journey
                </p>
            </div>

            <div class="cosmic-dashboard-grid">
                <!-- Quick Stats Cards -->
                <Card class="cosmic-dashboard-card">
                    <template #header>
                        <div class="cosmic-card-icon cosmic-card-icon-primary">
                            <i class="pi pi-chart-line"></i>
                        </div>
                    </template>
                    <template #title>Analytics</template>
                    <template #content>
                        <p class="cosmic-card-description">
                            Track your cosmic journey and stellar achievements
                        </p>
                        <div class="cosmic-stat-value">1,337</div>
                        <div class="cosmic-stat-label">Stellar Points</div>
                    </template>
                </Card>

                <Card class="cosmic-dashboard-card">
                    <template #header>
                        <div class="cosmic-card-icon cosmic-card-icon-secondary">
                            <i class="pi pi-users"></i>
                        </div>
                    </template>
                    <template #title>Community</template>
                    <template #content>
                        <p class="cosmic-card-description">
                            Connect with fellow space travelers
                        </p>
                        <div class="cosmic-stat-value">42</div>
                        <div class="cosmic-stat-label">Active Travelers</div>
                    </template>
                </Card>

                <Card class="cosmic-dashboard-card">
                    <template #header>
                        <div class="cosmic-card-icon cosmic-card-icon-accent">
                            <i class="pi pi-star"></i>
                        </div>
                    </template>
                    <template #title>Achievements</template>
                    <template #content>
                        <p class="cosmic-card-description">
                            Your cosmic accomplishments and milestones
                        </p>
                        <div class="cosmic-stat-value">7</div>
                        <div class="cosmic-stat-label">Unlocked</div>
                    </template>
                </Card>

                <!-- Quick Actions -->
                <Card class="cosmic-dashboard-card cosmic-actions-card">
                    <template #title>Quick Actions</template>
                    <template #content>
                        <div class="cosmic-actions-grid">
                            <Button 
                                icon="pi pi-plus" 
                                label="Create Post" 
                                class="cosmic-action-btn"
                                severity="primary"
                            />
                            <Button 
                                icon="pi pi-cog" 
                                label="Settings" 
                                class="cosmic-action-btn"
                                severity="secondary"
                                outlined
                            />
                            <Button 
                                icon="pi pi-user" 
                                label="Profile" 
                                class="cosmic-action-btn"
                                severity="info"
                                outlined
                            />
                            <Button 
                                icon="pi pi-chart-bar" 
                                label="Reports" 
                                class="cosmic-action-btn"
                                severity="success"
                                outlined
                            />
                            <Button 
                                @click="() => $inertia.visit(route('admin.dashboard'))"
                                icon="pi pi-cog" 
                                label="Admin Panel" 
                                class="cosmic-action-btn"
                                severity="warning"
                                outlined
                            />
                        </div>
                    </template>
                </Card>
            </div>

            <!-- Status Bar -->
            <div class="cosmic-status-bar">
                <div class="cosmic-status-item">
                    <span class="cosmic-status-label">Current Theme:</span>
                    <Badge 
                        :value="currentTheme" 
                        :severity="isDarkMode ? 'contrast' : 'info'"
                        class="cosmic-status-badge"
                    />
                </div>
                <div class="cosmic-status-item">
                    <span class="cosmic-status-label">Status:</span>
                    <Badge 
                        value="Online" 
                        severity="success"
                        class="cosmic-status-badge"
                    />
                </div>
                <div class="cosmic-status-item">
                    <span class="cosmic-status-label">System:</span>
                    <Badge 
                        value="Operational" 
                        severity="info"
                        class="cosmic-status-badge"
                    />
                </div>
            </div>
        </div>
    </CosmicLayout>
</template>

<style scoped>
.cosmic-dashboard {
    padding: 2rem;
    max-width: 1400px;
    margin: 0 auto;
}

.cosmic-dashboard-header {
    text-align: center;
    margin-bottom: 3rem;
}

.cosmic-dashboard-title {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 1rem;
    color: white;
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

.cosmic-dashboard-subtitle {
    font-size: 1.2rem;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 0;
}

.cosmic-dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.cosmic-dashboard-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 1rem;
    transition: all 0.3s ease;
    overflow: hidden;
}

.cosmic-dashboard-card:hover {
    transform: translateY(-4px);
    border-color: var(--p-primary-color);
    box-shadow: 0 15px 30px rgba(147, 51, 234, 0.3);
}

.cosmic-actions-card {
    grid-column: span 2;
}

.cosmic-card-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    color: white;
    font-size: 1.5rem;
}

.cosmic-card-icon-primary {
    background: linear-gradient(135deg, var(--p-primary-color), var(--p-primary-600));
}

.cosmic-card-icon-secondary {
    background: linear-gradient(135deg, var(--p-secondary-color), var(--p-secondary-600));
}

.cosmic-card-icon-accent {
    background: linear-gradient(135deg, var(--p-accent-color), #be185d);
}

.cosmic-card-description {
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 1.5rem;
    text-align: center;
}

.cosmic-stat-value {
    font-size: 2.5rem;
    font-weight: 800;
    color: white;
    text-align: center;
    margin-bottom: 0.5rem;
}

.cosmic-stat-label {
    color: rgba(255, 255, 255, 0.7);
    text-align: center;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.cosmic-actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.cosmic-action-btn {
    width: 100%;
    justify-content: flex-start;
    padding: 1rem;
    transition: all 0.3s ease;
}

.cosmic-action-btn:hover {
    transform: translateY(-2px);
}

.cosmic-status-bar {
    display: flex;
    justify-content: center;
    gap: 2rem;
    flex-wrap: wrap;
    padding: 2rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 1rem;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.cosmic-status-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.cosmic-status-label {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.9rem;
}

.cosmic-status-badge {
    text-transform: capitalize;
}

@media (max-width: 768px) {
    .cosmic-dashboard {
        padding: 1rem;
    }

    .cosmic-dashboard-title {
        font-size: 2rem;
    }

    .cosmic-dashboard-subtitle {
        font-size: 1rem;
    }

    .cosmic-dashboard-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .cosmic-actions-card {
        grid-column: span 1;
    }

    .cosmic-actions-grid {
        grid-template-columns: 1fr;
    }

    .cosmic-status-bar {
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }
}

@media (max-width: 1024px) {
    .cosmic-actions-card {
        grid-column: span 1;
    }
}

:global(.dark) .cosmic-dashboard-card {
    background: rgba(0, 0, 0, 0.3);
    border-color: rgba(255, 255, 255, 0.1);
}

:global(.dark) .cosmic-dashboard-card:hover {
    border-color: var(--p-primary-color);
}

:global(.dark) .cosmic-status-bar {
    background: rgba(0, 0, 0, 0.2);
}
</style>
