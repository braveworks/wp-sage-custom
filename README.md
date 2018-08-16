# WordPress Sage Cutom スターターテーマ

Sage8 をベースにカスタマイズしたスターターテーマ。

- Gulp から Sage9 の Webpack に変更
- PHP7 や Composer、Blade などが NG な環境用に。

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

- `publicPath`: 公開時のテーマディレクトリまでのサーバールートパスを指定
- `devUrl`: 開発時のローカルサーバー URL
- `proxyUrl`: 開発時のプロキシ URL
- `cacheBusting`: キャッシュバスター時のファイルネームフォーマット。例："[name]\_[hash:8]"（ファイル名をかえない場合は"[name]"）

## Theme setup

Edit `lib/setup.php` to enable or disable theme features, setup navigation menus, post thumbnail sizes, and sidebars.

## Theme development

- Run `yarn` from the theme directory to install dependencies
- Update `assets/config.json` settings:
  - `devUrl` should reflect your local development hostname
  - `publicPath` should reflect your WordPress folder structure (`/wp-content/themes/sage` for non-[Bedrock](https://roots.io/bedrock/) installs)

### Build commands

- `yarn start` — Compile assets when file changes are made, start Browsersync session
- `yarn build` — Compile and optimize the files in your assets directory
- `yarn build:production` — Compile assets for production

※WordPress の`home_url`と`site_url`が異なることで CSS などが表示されない場合は、`SAGE_DIST_PATH`にアセットパスを指定してビルドする。

```sh
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
