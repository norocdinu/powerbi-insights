# PowerBI Insights — WordPress Theme

A modern, minimalist WordPress theme built for Power BI, data analytics, and Microsoft Fabric professionals. Clean reading experience, dark/light mode, custom Gutenberg blocks, and DAX code support out of the box.

![Theme Version](https://img.shields.io/badge/version-1.0.0-blue)
![WordPress](https://img.shields.io/badge/WordPress-6.0%2B-21759b)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-777bb4)
![License](https://img.shields.io/badge/license-GPL--2.0-green)

---

## Screenshots

> Add your screenshots here after activating the theme.
I am not shure how.
---

## Features

### Design
- **Dark / Light mode toggle** — persists via `localStorage`, respects system preference
- **Power BI blue accent** (`#0078D4`) throughout the UI
- **Mobile-first responsive** layout with a sticky header
- **Inter** typeface + **Fira Code** for code blocks
- No heavy frameworks — plain CSS with custom properties, vanilla JS

### Layout
- **Homepage** — featured hero post, numbered "Top Insights" section, latest articles grid
- **Single post** — auto-generated Table of Contents, reading progress bar, related posts, author bio
- **Sidebar** — newsletter signup, categories, recent posts, tag cloud
- **Archive / Search / 404** pages all styled consistently

### Custom Gutenberg Blocks
| Block | Description |
|---|---|
| `Power BI Tip` | Highlighted box — tip 💡, note 📝, warning ⚠️, or Power BI 📊 style |
| `DAX Snippet` | Syntax-coloured DAX code block with one-click copy button |
| `Power BI Embed` | Responsive iframe embed with branded header bar |

All three blocks also register as **shortcodes** for Classic Editor / page builders:

```
[pbi_tip type="tip" title="Pro Tip"]Use CALCULATE to change filter context.[/pbi_tip]

[dax label="DAX" description="Total Sales measure"]
Total Sales = SUMX(Sales, Sales[Qty] * Sales[Price])
[/dax]

[pbi_embed url="https://app.powerbi.com/reportEmbed?..." title="Sales Dashboard"]
```

### SEO & Performance
- Semantic HTML5 with proper ARIA roles
- `<link rel="preload">` for the hero image
- Scripts loaded with `defer`
- Emoji scripts removed
- XML-RPC disabled
- Reading time calculated server-side

---

## Requirements

| Requirement | Minimum |
|---|---|
| WordPress | 6.0 |
| PHP | 7.4 |
| Browser | Any modern browser (ES6+) |

---

## Installation

### Via WordPress Admin (recommended)
1. Download `powerbi-insights.zip` from the [Releases](../../releases) page
2. Go to **Appearance → Themes → Add New → Upload Theme**
3. Upload the zip and click **Install Now**
4. Click **Activate**

### Via FTP / File Manager
1. Extract `powerbi-insights.zip`
2. Upload the `powerbi-insights/` folder to `wp-content/themes/`
3. Go to **Appearance → Themes** and activate **PowerBI Insights**

---

## Configuration

### Menus
Go to **Appearance → Menus** and assign menus to:
- **Primary Navigation** — main header nav
- **Footer Column 1 / 2** — footer link columns
- **Social Links** — footer social icons

### Logo
Go to **Appearance → Customize → Site Identity**:
- Upload your logo under **Logo**
- Adjust **Logo Max Height (px)** — live preview included

### Widgets
Go to **Appearance → Widgets** and add widgets to **Main Sidebar**. If left empty, the theme shows a default set (search, newsletter, categories, recent posts, tags).

### Featured Post (Homepage Hero)
On any post, find the **PowerBI Insights Options** meta box in the editor sidebar:
- Check **Homepage Featured Post** to pin it as the hero
- Check **Top Insight** + set an **Insight Order** number to include it in the "Top Insights" section

### Newsletter
The newsletter form submits via AJAX to a WordPress action hook. Connect it to your email provider by hooking into:

```php
add_action( 'pbiins_newsletter_subscribe', function( string $email ) {
    // Add $email to Mailchimp, ConvertKit, etc.
} );
```

---

## File Structure

```
powerbi-insights/
├── style.css                   # Theme header + full CSS
├── functions.php               # Theme setup, enqueue, Customizer, AJAX
├── index.php                   # Homepage / blog index
├── single.php                  # Single post
├── page.php                    # Static page
├── archive.php                 # Category / tag / author / date archives
├── search.php                  # Search results
├── sidebar.php                 # Sidebar template
├── header.php                  # Site header, nav, search overlay
├── footer.php                  # Site footer
├── comments.php                # Comments template
├── searchform.php              # Custom search form
├── 404.php                     # 404 error page
├── assets/
│   ├── css/
│   │   └── blocks.css          # Gutenberg block + editor styles
│   └── js/
│       ├── theme.js            # Dark mode, nav, copy code, TOC, progress bar
│       └── editor-blocks.js    # Custom block editor UI (Gutenberg)
├── inc/
│   ├── custom-blocks.php       # Block registration + shortcodes
│   └── template-tags.php       # TOC injection, code wrap, helper functions
└── template-parts/
    ├── content.php             # Post card (grids)
    ├── content-featured.php    # Hero featured post
    └── content-none.php        # Empty state
```

---

## Custom Block Usage

### Power BI Tip Block
Available in the Gutenberg block inserter under **Power BI Insights → Power BI Tip**.

Set the **Type** in the block settings panel:
- `tip` — blue, lightbulb icon
- `note` — grey, notepad icon
- `warning` — amber, warning icon
- `powerbi` — gradient, chart icon

### DAX Snippet Block
Automatically adds a language label and copy button. Set a custom **Language Label** (e.g., `M Query`, `SQL`) if needed.

**Syntax colour classes** (add manually via HTML block if needed):

| Class | Colour | Use for |
|---|---|---|
| `.kw` | Blue | Keywords (`VAR`, `RETURN`, `IF`) |
| `.fn` | Yellow | Functions (`CALCULATE`, `SUMX`) |
| `.str` | Orange | Strings |
| `.num` | Green | Numbers |
| `.cm` | Grey italic | Comments |
| `.tbl` | Teal | Table names |

### Power BI Embed Block
Paste the embed URL from **Power BI → Share → Embed report → Website or portal**.

Only `app.powerbi.com` and `msit.powerbi.com` URLs are accepted — all others are blocked for security.

---

## Changelog

### 1.0.0
- Initial release

---

## License

[GNU General Public License v2.0](LICENSE)

WordPress themes are required to be GPL-compatible. This theme is licensed under the GPL v2 or later.
