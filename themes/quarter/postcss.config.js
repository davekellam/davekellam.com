/** @type {import('postcss-load-config').Config} */
const config = (ctx) => ({
  plugins: [
    require('postcss-import'),
    ctx.env === 'production'
      ? require('cssnano')({ preset: 'default' })
      : false,
  ].filter(Boolean),
});

module.exports = config;
