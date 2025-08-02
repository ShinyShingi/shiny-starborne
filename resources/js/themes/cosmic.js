import { definePreset } from '@primevue/themes';
import Aura from '@primevue/themes/aura';

// Cosmic Nebula Color Palette
const cosmicColors = {
    // Primary cosmic purple
    primary: {
        50: '#f3e8ff',   // Very light purple
        100: '#e9d5ff',  // Light purple
        200: '#d8b4fe',  // Lighter purple
        300: '#c084fc',  // Light-medium purple
        400: '#a855f7',  // Medium purple
        500: '#9333ea',  // Primary purple
        600: '#7c3aed',  // Darker purple
        700: '#6d28d9',  // Dark purple
        800: '#5b21b6',  // Very dark purple
        900: '#4c1d95',  // Deepest purple
        950: '#2e1065'   // Ultra dark purple
    },
    
    // Nebula blue-purple
    secondary: {
        50: '#eff6ff',   // Very light blue
        100: '#dbeafe',  // Light blue
        200: '#bfdbfe',  // Lighter blue
        300: '#93c5fd',  // Light-medium blue
        400: '#60a5fa',  // Medium blue
        500: '#3b82f6',  // Primary blue
        600: '#2563eb',  // Darker blue
        700: '#1d4ed8',  // Dark blue
        800: '#1e40af',  // Very dark blue
        900: '#1e3a8a',  // Deepest blue
        950: '#172554'   // Ultra dark blue
    },
    
    // Cosmic magenta
    accent: {
        50: '#fdf2f8',   // Very light magenta
        100: '#fce7f3',  // Light magenta
        200: '#fbcfe8',  // Lighter magenta
        300: '#f9a8d4',  // Light-medium magenta
        400: '#f472b6',  // Medium magenta
        500: '#ec4899',  // Primary magenta
        600: '#db2777',  // Darker magenta
        700: '#be185d',  // Dark magenta
        800: '#9d174d',  // Very dark magenta
        900: '#831843',  // Deepest magenta
        950: '#500724'   // Ultra dark magenta
    },
    
    // Cosmic grays with purple tint
    surface: {
        0: '#ffffff',     // Pure white
        50: '#fafafa',    // Off white
        100: '#f5f5f5',   // Very light gray
        200: '#e5e5e5',   // Light gray
        300: '#d4d4d4',   // Medium-light gray
        400: '#a3a3a3',   // Medium gray
        500: '#737373',   // Primary gray
        600: '#525252',   // Dark gray
        700: '#404040',   // Very dark gray
        800: '#262626',   // Near black
        900: '#171717',   // Almost black
        950: '#0a0a0a'    // Pure black
    }
};

// Create the cosmic theme preset
const Cosmic = definePreset(Aura, {
    semantic: {
        primary: cosmicColors.primary,
        colorScheme: {
            light: {
                primary: {
                    color: cosmicColors.primary[600],
                    contrastColor: '#ffffff',
                    hoverColor: cosmicColors.primary[700],
                    activeColor: cosmicColors.primary[800]
                },
                highlight: {
                    background: cosmicColors.primary[50],
                    focusBackground: cosmicColors.primary[100],
                    color: cosmicColors.primary[700],
                    focusColor: cosmicColors.primary[800]
                },
                surface: {
                    0: '#ffffff',
                    50: cosmicColors.surface[50],
                    100: cosmicColors.surface[100],
                    200: cosmicColors.surface[200],
                    300: cosmicColors.surface[300],
                    400: cosmicColors.surface[400],
                    500: cosmicColors.surface[500],
                    600: cosmicColors.surface[600],
                    700: cosmicColors.surface[700],
                    800: cosmicColors.surface[800],
                    900: cosmicColors.surface[900],
                    950: cosmicColors.surface[950]
                }
            },
            dark: {
                primary: {
                    color: cosmicColors.primary[400],
                    contrastColor: cosmicColors.surface[900],
                    hoverColor: cosmicColors.primary[300],
                    activeColor: cosmicColors.primary[200]
                },
                highlight: {
                    background: 'rgba(147, 51, 234, 0.16)',
                    focusBackground: 'rgba(147, 51, 234, 0.24)',
                    color: 'rgba(147, 51, 234, 0.87)',
                    focusColor: 'rgba(147, 51, 234, 0.92)'
                },
                surface: {
                    0: '#ffffff',
                    50: cosmicColors.surface[50],
                    100: cosmicColors.surface[100],
                    200: cosmicColors.surface[200],
                    300: cosmicColors.surface[300],
                    400: cosmicColors.surface[400],
                    500: cosmicColors.surface[500],
                    600: cosmicColors.surface[600],
                    700: cosmicColors.surface[700],
                    800: cosmicColors.surface[800],
                    900: cosmicColors.surface[900],
                    950: cosmicColors.surface[950]
                }
            }
        }
    }
});

export default Cosmic;
export { cosmicColors };
