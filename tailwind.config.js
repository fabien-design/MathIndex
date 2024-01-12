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
    },
  },
  plugins: [
    require('flowbite/plugin')
  ],
}

