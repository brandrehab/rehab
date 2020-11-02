const Path = require('path')
const TerserJSPlugin = require('terser-webpack-plugin')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin')
const ManifestPlugin = require('webpack-manifest-plugin')
const PurgecssPlugin = require('purgecss-webpack-plugin')
const WebpackShellPlugin = require('webpack-shell-plugin-next')
const ExtraWatchWebpackPlugin = require('extra-watch-webpack-plugin')
const ImageMinimizerPlugin = require("image-minimizer-webpack-plugin")
const Glob = require('glob-all')

const theme = 'client'
const jqueryVersion = 'jquery.slim.js'

const cssContent = [
  Path.resolve(__dirname, 'web/themes/custom/' + theme + '/layout/*.html.twig'),
  Path.resolve(__dirname, 'web/modules/custom/**/templates/*.html.twig'),
  Path.resolve(__dirname, 'node_modules/bootstrap/js/src/*.js'),
]

const cssWhitelistPatterns = [
]

module.exports = {
  entry: {
    vendor: Path.resolve(__dirname, 'build/js/vendor.js'),
    app: Path.resolve(__dirname, 'build/js/app.js'),
    fonts: Path.resolve(__dirname, 'build/js/fonts.js')
  },
  output: {
    filename: '[name].[chunkhash].js',
    path: Path.resolve(__dirname, 'web/dist/'),
    publicPath: '/dist/'
  },
  mode: 'production',
  watch: true,
  watchOptions: {
    ignored: ['node_modules', 'build/images/favicon']
  },
  resolve: {
    extensions: ['.js'],
    alias: {
      'jquery': 'jquery/dist/' + jqueryVersion,
    }
  },
  optimization: {
    minimizer: [
      new TerserJSPlugin({}),
      new OptimizeCssAssetsPlugin({})
    ]
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: '[name].[chunkhash].css',
    }),
    new PurgecssPlugin({
      paths: () => Glob.sync(cssContent),
      only: ['vendor', 'app', 'fonts'],
      whitelistPatterns: cssWhitelistPatterns
    }),
    new ManifestPlugin({
      fileName: 'manifest.json',
      filter: (file) => {
        if (file.name.match(/\.(scss)$/i)) {
          return false
        }
        if (file.name === 'fonts.js') {
          return false
        }
        if (file.name.match(/\.js\.LICENSE\.txt/)) {
          return false
        }
        return true
      },
      map: (file) => {
        file.name = file.name.replace(/(\.[a-f0-9]{32})(\..*)$/i, '$2');
        return file;
      }
    }),
    new WebpackShellPlugin({
      onWatchRun: {
        scripts: [
          'echo Cleaning web/dist',
          'rm -rf web/dist'
        ]
      },
      onBuildExit: {
        scripts: [
          'echo Triggering browser reload',
          'touch build.version'
        ]
      },
      dev: false
    }),
    new ExtraWatchWebpackPlugin({
      files: [
        Path.resolve(__dirname, 'app/**/*.php'),
        Path.resolve(__dirname, 'web/modules/custom/**/*.*'),
        Path.resolve(__dirname, 'web/themes/custom/' + theme + '/layout/*.html.twig')
      ],
    }),
    new ImageMinimizerPlugin({
      cache: true,
      minimizerOptions: {
        plugins: [
          ["gifsicle", {
            interlaced: true
          }],
          ["jpegtran", {
            progressive: true
          }],
          ["optipng", {
            optimizationLevel: 5
          }],
          ["svgo", {
            plugins: [{removeViewBox: false}]
          }]
        ]
      }
    })
  ],
  module: {
    rules: [
      {
        test: /\.(sa|sc|c)ss$/i,
        use: [
          {
            loader: MiniCssExtractPlugin.loader,
          },
          'css-loader',
          'sass-loader'
        ]
      },
      {
        test: /\.(eot|ttf|woff|woff2)$/i,
        loader: 'file-loader',
        options: {
          name: '[name].[hash].[ext]',
          outputPath: 'fonts',
          publicPath: '/dist/fonts'
        }
      },
      {
        test: /\.(png|jpe?g|gif|svg)$/i,
        use: [
          {
            loader: 'file-loader',
            options: {
              name: '[name].[hash].[ext]',
              outputPath: 'images',
              publicPath: '/dist/images'
            },
          },
        ],
      },
      {
        test: /\.js$/i,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader'
        }
      }
    ]
  }
}
