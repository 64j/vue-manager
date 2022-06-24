const { defineConfig } = require('@vue/cli-service')
module.exports = defineConfig({
  publicPath: './',
  outputDir: '../../../../vue-manager',
  assetsDir: 'static',
  transpileDependencies: true,
  productionSourceMap: false,
  filenameHashing: true,

  configureWebpack: {
    optimization: {
      splitChunks: false
    }
  },

  pluginOptions: {
    i18n: {
      locale: 'en',
      fallbackLocale: 'en',
      localeDir: 'locales',
      enableLegacy: false,
      runtimeOnly: false,
      compositionOnly: false,
      fullInstall: true
    }
  }
})
