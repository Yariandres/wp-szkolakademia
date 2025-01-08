/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./*.{php,js,css,html}','./template/*.{php,html,js}','./inc/**/*.{php,js,css}','./templates/course/*.{php,html,js}'],
  theme: {
    extend: {},
  },
  plugins: [require('@tailwindcss/aspect-ratio'), require('@tailwindcss/forms')],
};
