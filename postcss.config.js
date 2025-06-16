module.exports = {
  plugins: [
    require("@fullhuman/postcss-purgecss")({
      content: [
        "./resources/views/**/*.php",
        "./resources/js/**/*.js"
      ],
      safelist: {
        standard: [/^is-/, /^has-/] // keep BEM state classes
      },
      defaultExtractor: (content) => content.match(/[A-Za-z0-9-_:/]+/g) || []
    })
  ]
};
