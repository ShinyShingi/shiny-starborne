<template>
    <div class="image-upload">
        <label class="upload-label">
            <i class="pi pi-image"></i>
            {{ label }}
        </label>
        
        <!-- Current Image Preview -->
        <div v-if="currentImageUrl" class="current-image">
            <div class="image-preview">
                <img :src="currentImageUrl" :alt="imageAlt" class="preview-img" />
                <div class="image-overlay">
                    <Button 
                        @click="removeCurrentImage"
                        icon="pi pi-trash"
                        severity="danger"
                        text
                        rounded
                        class="remove-btn"
                        v-tooltip="'Remove current image'"
                    />
                </div>
            </div>
            <small class="image-info">Current image</small>
        </div>

        <!-- Upload Area -->
        <div 
            class="upload-area"
            :class="{
                'upload-area-active': isDragActive,
                'upload-area-error': hasError
            }"
            @drop="onDrop"
            @dragover="onDragOver"
            @dragenter="onDragEnter"
            @dragleave="onDragLeave"
            @click="triggerFileInput"
        >
            <div v-if="uploading" class="upload-loading">
                <ProgressSpinner size="40" />
                <p>Uploading image...</p>
            </div>
            
            <div v-else-if="previewUrl" class="upload-preview">
                <img :src="previewUrl" alt="Preview" class="preview-img" />
                <div class="preview-overlay">
                    <Button 
                        @click.stop="removeImage"
                        icon="pi pi-trash"
                        severity="danger"
                        text
                        rounded
                        class="remove-btn"
                        v-tooltip="'Remove image'"
                    />
                    <Button 
                        @click.stop="triggerFileInput"
                        icon="pi pi-pencil"
                        severity="primary"
                        text
                        rounded
                        class="edit-btn"
                        v-tooltip="'Change image'"
                    />
                </div>
            </div>
            
            <div v-else class="upload-content">
                <i class="pi pi-cloud-upload upload-icon"></i>
                <h4>Drop image here or click to upload</h4>
                <p>Supports: JPEG, PNG, GIF, WebP (max 5MB)</p>
            </div>
        </div>

        <!-- Hidden File Input -->
        <input
            ref="fileInput"
            type="file"
            accept="image/jpeg,image/png,image/gif,image/webp"
            @change="onFileSelect"
            class="file-input"
        />

        <!-- Error Message -->
        <small v-if="errorMessage" class="error-message">
            {{ errorMessage }}
        </small>

        <!-- Help Text -->
        <small v-if="helpText" class="help-text">
            {{ helpText }}
        </small>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import axios from 'axios'
import Button from 'primevue/button'
import ProgressSpinner from 'primevue/progressspinner'

const props = defineProps({
    modelValue: String,
    label: {
        type: String,
        default: 'Featured Image'
    },
    helpText: {
        type: String,
        default: 'Upload an image to represent this content'
    },
    imageAlt: {
        type: String,
        default: 'Uploaded image'
    }
})

const emit = defineEmits(['update:modelValue'])

// State
const fileInput = ref(null)
const previewUrl = ref(null)
const uploading = ref(false)
const isDragActive = ref(false)
const errorMessage = ref('')

// Computed
const currentImageUrl = computed(() => props.modelValue)
const hasError = computed(() => !!errorMessage.value)

// Watch for external changes
watch(() => props.modelValue, (newValue) => {
    if (!newValue) {
        previewUrl.value = null
    }
})

// Methods
const triggerFileInput = () => {
    fileInput.value?.click()
}

const onFileSelect = (event) => {
    const file = event.target.files[0]
    if (file) {
        handleFile(file)
    }
}

const onDragEnter = (event) => {
    event.preventDefault()
    isDragActive.value = true
}

const onDragOver = (event) => {
    event.preventDefault()
}

const onDragLeave = (event) => {
    event.preventDefault()
    if (!event.currentTarget.contains(event.relatedTarget)) {
        isDragActive.value = false
    }
}

const onDrop = (event) => {
    event.preventDefault()
    isDragActive.value = false
    
    const files = event.dataTransfer.files
    if (files.length > 0) {
        handleFile(files[0])
    }
}

const handleFile = async (file) => {
    errorMessage.value = ''
    
    // Validate file type
    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp']
    if (!allowedTypes.includes(file.type)) {
        errorMessage.value = 'Please select a valid image file (JPEG, PNG, GIF, or WebP)'
        return
    }
    
    // Validate file size (5MB max)
    if (file.size > 5 * 1024 * 1024) {
        errorMessage.value = 'Image must be smaller than 5MB'
        return
    }
    
    // Create preview
    const reader = new FileReader()
    reader.onload = (e) => {
        previewUrl.value = e.target.result
    }
    reader.readAsDataURL(file)
    
    // Upload file
    await uploadFile(file)
}

const uploadFile = async (file) => {
    uploading.value = true
    
    const formData = new FormData()
    formData.append('image', file)
    
    try {
        const response = await axios.post(route('admin.images.upload'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        })
        
        if (response.data.success) {
            emit('update:modelValue', response.data.path)
            previewUrl.value = null // Clear preview since we now have the actual image
        } else {
            errorMessage.value = response.data.message || 'Failed to upload image'
            previewUrl.value = null
        }
    } catch (error) {
        errorMessage.value = error.response?.data?.message || 'Network error occurred while uploading'
        previewUrl.value = null
    } finally {
        uploading.value = false
    }
}

const removeImage = () => {
    previewUrl.value = null
    emit('update:modelValue', '')
    if (fileInput.value) {
        fileInput.value.value = ''
    }
}

const removeCurrentImage = async () => {
    if (!currentImageUrl.value) return
    
    try {
        const response = await axios.delete(route('admin.images.delete'), {
            data: {
                path: currentImageUrl.value
            }
        })
        
        if (response.data.success) {
            emit('update:modelValue', '')
        } else {
            errorMessage.value = response.data.message || 'Failed to delete image'
        }
    } catch (error) {
        errorMessage.value = error.response?.data?.message || 'Network error occurred while deleting image'
    }
}
</script>

<style scoped>
.image-upload {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.upload-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: white;
    font-weight: 600;
    font-size: 0.95rem;
}

.current-image {
    margin-bottom: 1rem;
}

.image-preview,
.upload-preview {
    position: relative;
    width: 200px;
    height: 120px;
    border-radius: 0.75rem;
    overflow: hidden;
    background: rgba(255, 255, 255, 0.1);
}

.preview-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-overlay,
.preview-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.image-preview:hover .image-overlay,
.upload-preview:hover .preview-overlay {
    opacity: 1;
}

.upload-area {
    border: 2px dashed rgba(255, 255, 255, 0.3);
    border-radius: 1rem;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.05);
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.upload-area:hover {
    border-color: var(--p-primary-color);
    background: rgba(147, 51, 234, 0.1);
}

.upload-area-active {
    border-color: var(--p-primary-color);
    background: rgba(147, 51, 234, 0.2);
    transform: scale(1.02);
}

.upload-area-error {
    border-color: #ef4444;
    background: rgba(239, 68, 68, 0.1);
}

.upload-content h4 {
    color: white;
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
}

.upload-content p {
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
    font-size: 0.9rem;
}

.upload-icon {
    font-size: 3rem;
    color: rgba(255, 255, 255, 0.5);
    margin-bottom: 1rem;
}

.upload-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}

.upload-loading p {
    color: white;
    margin: 0;
}

.file-input {
    display: none;
}

.image-info {
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.85rem;
    margin-top: 0.5rem;
}

.error-message {
    color: #ef4444;
    font-size: 0.85rem;
}

.help-text {
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.85rem;
}

.remove-btn,
.edit-btn {
    width: 2.5rem;
    height: 2.5rem;
}

/* Responsive */
@media (max-width: 768px) {
    .upload-area {
        padding: 1.5rem;
        min-height: 150px;
    }
    
    .upload-content h4 {
        font-size: 1rem;
    }
    
    .upload-icon {
        font-size: 2rem;
    }
    
    .image-preview,
    .upload-preview {
        width: 150px;
        height: 90px;
    }
}

/* Dark mode adjustments */
:global(.dark) .upload-area {
    background: rgba(0, 0, 0, 0.2);
    border-color: rgba(255, 255, 255, 0.2);
}

:global(.dark) .upload-area:hover {
    background: rgba(147, 51, 234, 0.15);
}

:global(.dark) .image-preview,
:global(.dark) .upload-preview {
    background: rgba(0, 0, 0, 0.3);
}
</style>