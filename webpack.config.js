const path = require('path');
const IgnoreEmitPlugin = require('ignore-emit-webpack-plugin');
module.exports = (env, options) => {
    return {
        entry: {
            bundleCSS: './src/scss/index.scss',
            bundle: './src/ts/index.ts',
            // contact: './src/ts/contact.ts',
        },
        module: {
            rules: [{
                test: /\.ts$/,
                use: 'ts-loader',
                include: [path.resolve(__dirname, 'src/ts')]
            },
                {
                    test: /\.scss$/,
                    use: [{
                        loader: 'file-loader',
                        options: {
                            name: 'style.css',
                        }
                    },
                        {
                            loader: 'extract-loader'
                        },
                        {
                            loader: 'css-loader',
                            options: {
                                url: false,
                            },
                        },
                        {
                            loader: 'postcss-loader'
                        },
                        {
                            loader: 'sass-loader',
                            options: {
                                implementation: require('sass'),
                                sassOptions: {
                                    outputStyle: options.mode === 'production' ? 'compressed' : null,
                                },
                            },
                        }
                    ]
                }
            ]
        },
        resolve: {
            extensions: ['.ts', '.js']
        },
        plugins: [
            new IgnoreEmitPlugin(["bundleCSS.js"])
        ],
        output: {
            filename: "assets/js/[name].js",
            libraryTarget: 'umd',
            path: path.resolve(__dirname, '')
        }
    }
}
