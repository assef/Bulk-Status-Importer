# Bulk Status Importer

A lightweight, free, and open source WordPress plugin to bulk-update the status of posts, pages, or custom post types from a CSV file.

Ideal for sites with large content inventories needing to quickly switch post statuses (e.g., mass-draft outdated posts, publish scheduled content, or move items to trash).

---

## ✨ Features

- ✅ Bulk-change post status from a CSV upload
- ✅ Supports all post types (including custom types)
- ✅ Handles common statuses: publish, draft, pending, future, private, trash
- ✅ Special commands: delete, deleted, or trash move posts to Trash
- ✅ Admin page in WordPress for easy CSV upload

---

## 🧠 Use Case

Need to quickly manage hundreds (or thousands) of posts? This plugin lets you:

- ✅ Prepare a simple CSV with URLs and desired statuses
- ✅ Upload via the WordPress admin
- ✅ Apply changes in seconds

---

## 📦 Example CSV

The CSV file must contain these columns:

- url: Full URL of the post, page, or custom post type
- status: Desired status

### Example:

```csv
url,status
https://yoursite.com/post-1,draft
https://yoursite.com/post-2,delete
https://yoursite.com/post-3,publish
```

✅ Accepted statuses:
publish, draft, pending, future, private, trash

✅ Special commands:
delete, deleted, trash → moves the post to Trash

---

## 🛠 Installation

1. Clone or download this repo into your WordPress plugins folder: wp-content/plugins/bulk-status-importer/
2. Activate the plugin from the **WordPress admin dashboard**.
3. Go to Bulk Status Importer in the admin menu to upload your CSV file.

---

## ⚙️ Requirements

- WordPress 5.0+ (tested up to 6.5+)
- User with manage_options capability to access the importer page

## 🧑‍💻 Developer Notes

- The plugin attempts to match posts by their full URL. It uses url_to_postid() as well as a fallback that looks up the path for custom post types.
- Supports all registered post types, not only built-in ones.
- Status values are validated against WordPress’s accepted post statuses.

## 📄 License

[MIT License](https://mit-license.org/)

Copyright (c) 2025 Leonardo Assef

## 🤝 Contributing

1. Pull requests and suggestions are welcome!
2. Fork the repo
3. Create a new branch (git checkout -b feature/my-feature)
4. Commit your changes (git commit -am 'Add some feature')
5. Push and open a PR (git push origin feature/my-feature)

## 🙋‍♂️ Author

Leonardo Assef

GitHub: [@assef](https://github.com/assef)

[Linkedin](https://www.linkedin.com/in/leonardo-assef/)
