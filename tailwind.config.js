/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./resources/**/*.vue",
  ],
  theme: {
    extend: {
        colors: {
            sky: '#06B0D2',
            cloud: '#CAF0F8',
            fog: '#BDD7DC'
        },
        inset: {
            '120px': '120px',
        }
    },
  },
  plugins: [
      require('tailwind-scrollbar')
  ],
}
