/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
    "./node_modules/flowbite/**/*.js",
    "./src/Form/**/*.php"
  ],
  theme: {
    extend: {},
    colors: {
      'cse' : '#1B3168',
      'stone-500' : '#78716c',
      'neutral-500' : '#737373'
    },
  },
  plugins: [
    require('flowbite/plugin')
  ],
}

