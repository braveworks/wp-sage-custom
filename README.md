# WordPress Sage Cutom スターターテーマ

[Sage8](https://github.com/roots/sage/blob/8.5.4/README.md#theme-installation) をベースにカスタマイズしたスターターテーマ。

オリジナルはGulp＋Bowerですが、assetsのビルドを[Sage9のWebpack](https://roots.io/sage/docs/theme-development-and-building/)に置き換えています。

Based on Sage8.5.4 ｜ Webpack assets Builder : Based on Sage9.0.1

## Requirements

- [WordPress](https://wordpress.org/) >= 4.7
- [PHP](https://secure.php.net/manual/en/install.php) >= 5.4 (with [`php-mbstring`](https://secure.php.net/manual/en/book.mbstring.php) enabled)
- [Node.js](http://nodejs.org/) >= 6.9.x
- [Yarn](https://yarnpkg.com/en/docs/install)

## Features

- Sass for stylesheets
- Modern JavaScript（ES2015）
- [Webpack](https://webpack.github.io/) for compiling assets, optimizing images, and concatenating and minifying files
- [Browsersync](http://www.browsersync.io/) for synchronized browser testing

## setting

`yarn`（または `npm i`）でモジュールインストール

作業内容にあわせて`assets/config.json`の設定を変更する。

- `publicPath`: 公開時のテーマディレクトリまでのサーバールートパスを指定（ex: `/wp-content/themes/{theme_name}`）
- `devUrl`: 開発時のローカルサーバーのURLを指定
- `proxyUrl`: 開発時のプロキシURL
- `cacheBusting`: キャッシュ対策ファイル名フォーマット。（ex: "[name]\_[hash:8]" ファイル名をかえない場合は "[name]"）

### Build commands

- `yarn start` — Compile assets when file changes are made, start Browsersync session
- `yarn build` — Compile and optimize the files in your assets directory
- `yarn build:production` — Compile assets for production

※本番とローカルで、WordPress の`home_url`と`site_url`が違うことでCSSなどが表示されない場合は、`SAGE_DIST_PATH`にルートからdistディレクトリまでのパスを指定してビルドする。

```sh
# 例： 本番が WP/以下にある場合
SAGE_DIST_PATH=/wp/wp-content/themes/{themename}/dist/ yarn build:production
```

## Theme structure

```sh
.
├── assets
│ 　　　├── build/         # Webpack関連ファイル
│ 　　　├── config.json    # ビルド設定ファイル
│ 　　　├── fonts/         # フォント
│ 　　　├── images/        # 画像
│ 　　　├── scripts/       # JS
│ 　　　└── styles/        # Scss
├── lang/                 # 言語ファイル
├── lib/                  # functions.php で読み込む関数などのphp
├── templates/            # get_template_part で読み込むテンプレート
├── 404.php               # 404ページテンプレート
├── LICENSE.md            # sageライセンス
├── README.md
├── base.php              # ベーステンプレート（全テンプレートのラッパーになるテンプレート）
├── functions.php         # テーマ関数のfunctions.php 関数などはlib/ディレクトリからインクルードする
├── index.php             # index,archiveページのテンプレート
├── package.json          # npm設定
├── page.php              # 固定ページテンプレーﾄ
├── screenshot.png
├── search.php            # 検索結果ページテンプレート
├── single.php            # 投稿のシングルページテンプレート
├── style.css             # テーマCSS（ここにはテーマ設定のみでCSSは記述しない）
└── template-custom.php   # カスタムテンプレーﾄのサンプル
```

> 記載のないファイルやディレクトリは、意味がわからない場合は無視してください。

## 備考

ローカル環境でのドメインが`.local` だと、BrowserSyncの動作が重くなります。
Local by Flywheel などを使用する場合は`.test`などにしてください。
