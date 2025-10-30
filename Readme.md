# Public Info Module (Drupal 10/11)

This project provides a custom Drupal module (`public_info`) that fetches and displays public API data, along with a configuration form for admin settings.

---

## 🚀 Setup Instructions

### 1️⃣ Clone the Repository
```bash
git clone <repository-url>
cd <project-folder>
```

---

### 2️⃣ Start the DDEV Environment
```bash
ddev start
```

---

### 3️⃣ Import the Database
Make sure you have the `solaredge.sql.gz` database dump file, then run:
```bash
ddev import-db --file=solaredge.sql.gz
```

---

### 4️⃣ Login Credentials

#### 🔑 Admin User
- **Username:** `sahil@bigsteptech.com`
- **Password:** `L3@rnNow0507!`

#### 🧑‍💻 Authenticated User (Content Editor)
- **Username:** `sahil1`
- **Password:** `test`

To manage permissions, visit:
```
/admin/people/permissions
```

---

### 5️⃣ Test the Public Info API Page
Visit the following route to view API data:
```
/public-info
```

---

### 6️⃣ Access the Configuration Form
Admin configuration page for module settings:
```
/admin/config/services/public-info
```

---

## 🧩 Module Overview
| Feature | Description |
|----------|-------------|
| **Public Info Controller** | Displays fetched API data on `/public-info` route |
| **Settings Form** | Admin configurable options under `/admin/config/services/public-info` |
| **Permissions** | Granular control via Drupal’s permissions UI |

---

## 🧰 Requirements
- Drupal 10.x or 11.x
- PHP 8.1+
- DDEV environment

---

## 💡 Tips
- Run `drush cr` after any code changes to clear cache.
- If routes or controllers are updated, ensure file paths and namespaces match PSR-4 standards.
- Use `ddev ssh` to access the container if needed for Drush commands.

---

## 📄 License
This project is licensed under the MIT License.
