# Quarter

A minimal monospace WordPress theme for davekellam.com.

## Design

- Monospace font stack (`SF Mono`, `ui-monospace`, `Cascadia Code`, `Menlo`, `Consolas`, …)
- Royal blue links (`#4169e1` / `midnightblue` hover)
- Centered content at `42rem`, wide at `64rem`
- All design tokens live in `theme.json` (v3); CSS references them via `var(--wp--preset--*)`

## Structure

```
quarter/
├── theme.json          # v3 design tokens (typography, color, spacing, layout)
├── functions.php       # Bootstrap – loads includes/
├── includes/
│   ├── init.php        # Theme setup + wp_register_block_template() (WP 6.7+)
│   ├── styles.php      # Stylesheet enqueueing
│   ├── scripts.php     # Script enqueueing (minimal)
│   └── template-tags.php  # quarter_post_date(), quarter_pagination(), …
├── src/css/            # Source CSS (PostCSS @import)
│   ├── style.css       # Entry point
│   ├── _base.css
│   ├── _layout.css
│   ├── _typography.css
│   ├── _nav.css
│   ├── _components.css
│   └── _blocks.css
└── dist/css/           # Compiled output (committed)
    └── style.css
```

Classic PHP templates: `header.php`, `footer.php`, `index.php`, `single.php`,
`page.php`, `archive.php`, `search.php`, `404.php`, `comments.php`.

## Block Templates (PHP API)

`includes/init.php` registers block templates via `wp_register_block_template()`
(introduced in WordPress 6.7). Classic PHP template files take precedence when
present. The registered templates serve as block-based fallbacks and expose the
templates to the programmatic template registry without enabling the full Site Editor.

## CSS Build

```bash
npm install

# development (unminified, with source)
npm run build

# watch mode
npm run watch

# production (minified via cssnano)
npm run build:prod
```

PostCSS resolves `@import` statements and (in production) runs cssnano.
No transpilation — the output targets modern browsers only.
