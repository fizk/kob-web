module.exports = {
//   parser: 'sugarss',
  plugins: [
    require('postcss-import')(),
    require('postcss-preset-env')({
        stage: 3,
        features: {
            'nesting-rules': true
        }
     }),
  ],
}
