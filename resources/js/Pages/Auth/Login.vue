<script setup>
import CosmicLayout from '@/Components/Cosmic/CosmicLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import Checkbox from 'primevue/checkbox';
import Button from 'primevue/button';
import Message from 'primevue/message';
import Card from 'primevue/card';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Sign In to Shiny Starborne" />
    
    <CosmicLayout 
        title="Sign In" 
        background-variant="card"
        :show-header="false"
    >
        <div class="cosmic-auth-container">
            <div class="cosmic-auth-wrapper">
                <Card class="cosmic-auth-card">
                    <template #header>
                        <div class="cosmic-auth-header">
                            <div class="cosmic-auth-logo">
                                <i class="pi pi-sparkles cosmic-logo-icon"></i>
                            </div>
                            <h1 class="cosmic-auth-title">
                                Welcome Back to the 
                                <span class="cosmic-gradient-text">Cosmos</span>
                            </h1>
                            <p class="cosmic-auth-subtitle">
                                Sign in to continue your stellar journey
                            </p>
                        </div>
                    </template>
                    
                    <template #content>
                        <Message 
                            v-if="status" 
                            severity="success" 
                            :closable="false"
                            class="cosmic-auth-message"
                        >
                            {{ status }}
                        </Message>

                        <form @submit.prevent="submit" class="cosmic-auth-form">
                            <div class="cosmic-form-group">
                                <label for="email" class="cosmic-form-label">Email Address</label>
                                <InputText
                                    id="email"
                                    type="email"
                                    v-model="form.email"
                                    placeholder="Enter your email"
                                    class="cosmic-form-input"
                                    :class="{ 'p-invalid': form.errors.email }"
                                    required
                                    autofocus
                                    autocomplete="username"
                                />
                                <small v-if="form.errors.email" class="cosmic-error-message">
                                    {{ form.errors.email }}
                                </small>
                            </div>

                            <div class="cosmic-form-group">
                                <label for="password" class="cosmic-form-label">Password</label>
                                <Password
                                    id="password"
                                    v-model="form.password"
                                    placeholder="Enter your password"
                                    class="cosmic-form-input"
                                    :class="{ 'p-invalid': form.errors.password }"
                                    :feedback="false"
                                    toggle-mask
                                    required
                                    autocomplete="current-password"
                                />
                                <small v-if="form.errors.password" class="cosmic-error-message">
                                    {{ form.errors.password }}
                                </small>
                            </div>

                            <div class="cosmic-form-group cosmic-checkbox-group">
                                <div class="cosmic-checkbox-wrapper">
                                    <Checkbox 
                                        v-model="form.remember" 
                                        input-id="remember" 
                                        :binary="true"
                                        class="cosmic-checkbox"
                                    />
                                    <label for="remember" class="cosmic-checkbox-label">
                                        Remember me for 30 days
                                    </label>
                                </div>
                            </div>

                            <div class="cosmic-form-actions">
                                <Button
                                    type="submit"
                                    label="Sign In"
                                    icon="pi pi-sign-in"
                                    class="cosmic-submit-btn"
                                    :loading="form.processing"
                                    :disabled="form.processing"
                                />
                                
                                <Link
                                    v-if="canResetPassword"
                                    :href="route('password.request')"
                                    class="cosmic-auth-link"
                                >
                                    Forgot your password?
                                </Link>
                            </div>
                        </form>
                    </template>
                    
                    <template #footer>
                        <div class="cosmic-auth-footer">
                            <p class="cosmic-footer-text">
                                Don't have an account? 
                                <Link 
                                    :href="route('register')" 
                                    class="cosmic-auth-link cosmic-auth-link-bold"
                                >
                                    Join the cosmic community
                                </Link>
                            </p>
                        </div>
                    </template>
                </Card>
            </div>
        </div>
    </CosmicLayout>
</template>

<style scoped>
.cosmic-auth-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
}

.cosmic-auth-wrapper {
    width: 100%;
    max-width: 450px;
}

.cosmic-auth-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 2rem;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.cosmic-auth-header {
    text-align: center;
    padding: 2rem 2rem 1rem;
}

.cosmic-auth-logo {
    margin-bottom: 1.5rem;
}

.cosmic-logo-icon {
    font-size: 3rem;
    color: var(--p-primary-color);
    animation: cosmic-glow 2s ease-in-out infinite alternate;
}

@keyframes cosmic-glow {
    0% { 
        color: var(--p-primary-color);
        filter: drop-shadow(0 0 10px var(--p-primary-color));
    }
    100% { 
        color: var(--p-accent-color);
        filter: drop-shadow(0 0 20px var(--p-accent-color));
    }
}

.cosmic-auth-title {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: white;
}

.cosmic-gradient-text {
    background: linear-gradient(135deg, var(--p-primary-color), var(--p-accent-color));
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}

.cosmic-auth-subtitle {
    color: rgba(255, 255, 255, 0.8);
    font-size: 1rem;
    margin-bottom: 0;
}

.cosmic-auth-message {
    margin-bottom: 1.5rem;
}

.cosmic-auth-form {
    padding: 0 2rem 1rem;
}

.cosmic-form-group {
    margin-bottom: 1.5rem;
}

.cosmic-form-label {
    display: block;
    color: white;
    font-weight: 600;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.cosmic-form-input {
    width: 100%;
}

.cosmic-form-input :deep(.p-inputtext) {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    border-radius: 0.75rem;
    padding: 1rem;
    transition: all 0.3s ease;
}

.cosmic-form-input :deep(.p-inputtext:focus) {
    border-color: var(--p-primary-color);
    box-shadow: 0 0 0 2px rgba(147, 51, 234, 0.2);
}

.cosmic-form-input :deep(.p-inputtext::placeholder) {
    color: rgba(255, 255, 255, 0.5);
}

.cosmic-checkbox-group {
    margin: 1rem 0 2rem;
}

.cosmic-checkbox-wrapper {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.cosmic-checkbox-label {
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.9rem;
    cursor: pointer;
}

.cosmic-form-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    align-items: center;
}

.cosmic-submit-btn {
    width: 100%;
    background: linear-gradient(135deg, var(--p-primary-color), var(--p-primary-600));
    border: none;
    padding: 1rem;
    border-radius: 0.75rem;
    font-size: 1rem;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(147, 51, 234, 0.3);
}

.cosmic-submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(147, 51, 234, 0.4);
}

.cosmic-auth-link {
    color: var(--p-primary-color);
    text-decoration: none;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.cosmic-auth-link:hover {
    color: var(--p-accent-color);
    text-decoration: underline;
}

.cosmic-auth-link-bold {
    font-weight: 600;
}

.cosmic-auth-footer {
    text-align: center;
    padding: 1rem 2rem 2rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.cosmic-footer-text {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
    margin: 0;
}

.cosmic-error-message {
    color: #ef4444;
    font-size: 0.85rem;
    margin-top: 0.25rem;
    display: block;
}

@media (max-width: 768px) {
    .cosmic-auth-container {
        padding: 1rem;
    }
    
    .cosmic-auth-card {
        border-radius: 1.5rem;
    }
    
    .cosmic-auth-header {
        padding: 1.5rem 1.5rem 1rem;
    }
    
    .cosmic-auth-form {
        padding: 0 1.5rem 1rem;
    }
    
    .cosmic-auth-footer {
        padding: 1rem 1.5rem 2rem;
    }
    
    .cosmic-auth-title {
        font-size: 1.5rem;
    }
}

:global(.dark) .cosmic-auth-card {
    background: rgba(0, 0, 0, 0.4);
    border-color: rgba(255, 255, 255, 0.1);
}

:global(.dark) .cosmic-form-input :deep(.p-inputtext) {
    background: rgba(0, 0, 0, 0.3);
    border-color: rgba(255, 255, 255, 0.2);
}
</style>
