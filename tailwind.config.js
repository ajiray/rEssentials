import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Open Sans', ...defaultTheme.fontFamily.sans],
                serif: ['Playfair Display', ...defaultTheme.fontFamily.serif],
                cursive: ['Great Vibes'],
            },
            colors: {
                green: '#62D2A2',
                yellow: '#f0c273',
                delete: '#d3503e',
                gold: '#d2b589',
                black: '#000000',
                velvet: '#212e51',
                blanc: '#fdfffe',
                marble: '#e8e5e0',
                silk: '#ede5da',
            },
        },
    },
    plugins: [require('daisyui')],
    daisyui: {
        themes: false,
        darkTheme: "light",
        base: true,
        styled: true,
        utils: true,
        prefix: "",
        logs: true,
        themeRoot: ":root",
    },
};
