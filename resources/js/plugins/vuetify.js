import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import '@mdi/font/css/materialdesignicons.css'

// Check initial theme from localStorage or system preference
const getInitialTheme = () => {
  const saved = localStorage.getItem('cosmic-theme')
  if (saved) {
    return saved === 'dark' ? 'dark' : 'light'
  }
  return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
}

const vuetify = createVuetify({
  components,
  directives,
  theme: {
    defaultTheme: getInitialTheme(),
    themes: {
      light: {
        dark: false,
        colors: {
          primary: '#6366f1',
          secondary: '#8b5cf6',
          accent: '#a855f7',
          error: '#ef4444',
          warning: '#f59e0b',
          info: '#3b82f6',
          success: '#10b981',
          background: '#ffffff',
          surface: '#f8fafc',
        }
      },
      dark: {
        dark: true,
        colors: {
          primary: '#818cf8',
          secondary: '#a78bfa',
          accent: '#c084fc',
          error: '#f87171',
          warning: '#fbbf24',
          info: '#60a5fa',
          success: '#34d399',
          background: '#0f172a',
          surface: '#1e293b',
        }
      }
    }
  }
})

export default vuetify