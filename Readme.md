# Public Info Module (Drupal 10/11)

This project provides a custom Drupal module (`public_info`) that fetches and displays public API data, along with a configuration form for admin settings.

---

## 🚀 Setup Instructions

### 1️⃣ Clone the Repository
```bash
git clone https://github.com/Sahilk2710/Solaredge.git
<img width="1448" height="566" alt="image" src="https://github.com/user-attachments/assets/338de4a3-12eb-4d38-a7d6-01a990dc24ff" />

cd <project-folder>
dde config (drupal11)
ddev start
ddev import-db --src=solaredge.sql.gz
ddev drush cr
ddev ddrush uli
php version ->php 8.3
Drupal Version ->drupal 11.2
mysql 

### 4️⃣ Login Credentials

####  Admin User
- **Username:** `sahil@bigsteptech.com`
- **Password:** `L3@rnNow0507!`

#### Authenticated User (Content Editor)
- **Username:** `sahil1`
- **Password:** `test`

To manage permissions, visit:
```
/admin/people/permissions

### 5️⃣ Test the Public Info API Page
Visit the following route to view API data:
```
/public-info
<img width="1850" height="976" alt="image" src="https://github.com/user-attachments/assets/7eac383e-c1b4-461a-a3c0-2fcbb5021bbb" />

### 6️⃣ Access the Configuration Form
Admin configuration page for module settings:
```
/admin/config/services/public-info
```
<img width="1623" height="856" alt="image" src="https://github.com/user-attachments/assets/d3299271-8073-4f1b-8b50-3ee7587677ad" />


---

## 🧩 Module Overview
| Feature | Description |
|----------|-------------|
| **Public Info Controller** | Displays fetched API data on `/public-info` route |
| **Settings Form** | Admin configurable options under `/admin/config/services/public-info` |
| **Permissions** | Granular control via Drupal’s permissions UI |


##  Tips
- Run `drush cr` after any code changes to clear cache.
- If routes or controllers are updated, ensure file paths and namespaces match PSR-4 standards.
- Use `ddev ssh` to access the container if needed for Drush commands.

---

## 📄 License
This project is licensed under the MIT License.
<img width="1860" height="816" alt="image" src="https://github.com/user-attachments/assets/c4928765-ddd8-4dca-9c85-fbdbcba96e3f" />
<img width="1911" height="617" alt="image" src="https://github.com/user-attachments/assets/32f6b2ce-40fd-4576-9588-39538260c818" />
<img width="1828" height="985" alt="image" src="https://github.com/user-attachments/assets/9ffedd54-7342-4ea5-8546-c2b411adfc16" />

