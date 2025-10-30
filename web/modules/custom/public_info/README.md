# 🛰️ Public Info Module

## 🧩 INTRODUCTION
The **Public Info** module displays recent **SpaceX launch data** fetched from the public [SpaceX API](https://api.spacexdata.com/v4/launches).
It demonstrates modern **Drupal 11** best practices — including **custom controllers**, **blocks**, **services**, **pagination**, **libraries**, **Twig theming**, and **configuration forms**.

---

## ✨ KEY FEATURES
✅ Fetches real-time launch data using **GuzzleHttp** service.
✅ Implements **custom service** (`public_info.api_client`) for API communication.
✅ Displays launches via **controller route** with built-in **pagination**.
✅ Provides a configurable **block plugin** to display latest launches anywhere on the site.
✅ Includes an **admin configuration form** to set cache TTL and result limit.
✅ Uses **Twig template** for responsive layout (images, dates, YouTube links).
✅ Loads **CSS** via **library system** (`public_info.libraries.yml`).
✅ Defines a **custom permission** to restrict access (`access public info page`).
✅ Demonstrates **dependency injection**, **caching**, and **Drupal coding standards**.

---

## 🧠 REQUIREMENTS
- Drupal 11.x
- PHP ≥ 8.1
- `drupal/core`
- `drush/drush`
- `guzzlehttp/guzzle` (included in Drupal core)

---

## ⚙️ INSTALLATION

1. Copy the module into your custom modules directory:
   `/web/modules/custom/public_info`

2. Enable the module using Drush:
   `ddev drush en public_info -y`

3. Clear cache:
   `ddev drush cr`

---

## 🌐 ROUTES & URLS

### 🔹 Frontend Display Page
View the latest SpaceX launch data at:
👉 `/public-info`
Example (on DDEV):
`https://solaredge.ddev.site/public-info`

**Controller:**
`src/Controller/PublicInfoController.php`

**Twig Template:**
`templates/public-info-launches.html.twig`

---

### 🔹 Admin Configuration Form
Configure cache TTL and number of results at:
👉 `/admin/config/public-info/settings`

**Form Class:**
`src/Form/PublicInfoSettingsForm.php`

**Config File:**
`config/install/public_info.settings.yml`

---

### 🔹 Block Plugin
You can place the **Public Info Block** anywhere from the Drupal admin UI:
👉 `/admin/structure/block`

**Block Class:**
`src/Plugin/Block/PublicInfoBlock.php`


Add the Public Info Block
To display launch data in a specific region:

Go to Structure → Block Layout (/admin/structure/block)

Click “Place block” in your desired region

Search for “Public Info Block”

Click “Place block”, adjust settings if needed, and save

This block uses the same API service and Twig template for rendering launch data.

---
