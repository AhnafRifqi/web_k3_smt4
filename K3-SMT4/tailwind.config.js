export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
    './node_modules/flowbite/**/*.js',
  ],
  darkMode: 'class',
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
      },
    },
  },
  safelist: [
    {
      pattern: /^(bg|text|border|ring)-(red|green|yellow|blue|orange|purple|gray)-(50|100|200|300|400|500|600|700|800|900)$/,
      variants: ['dark', 'hover'],
    },
  ],
  plugins: [
    require('@tailwindcss/forms'),
    require('flowbite/plugin'),
  ],
}