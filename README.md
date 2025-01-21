
# 📋 Checky - The Submission Checklist Generator 📋

[Checky]([url](https://checky.recommender-systems.com/)) is a **web-based and intuitive tool** that makes it easy to create, organize, and share submission checklists for academic papers, conferences, and research projects. If you want to cite Checky, please cite:

>Beel, Joeran, Bela Gipp, Dietmar Jannach, Alan Said, Lukas Wegmeth, and Tobias Vente. "Checky, the Paper-Submission Checklist Generator for Authors, Reviewers and LLMs." 2025. 47th European Conference on Information Retrieval (ECIR).

## ✨ Features
- **Checklist Generator**: Create, edit, and manage submission checklists with an intuitive UI.
- **Recommender System**: Get AI-powered suggestions for checklist items based on your input.
- **Checklist Archive**: Browse a growing repository of checklists from various conferences.
- **Export Options**: Export checklists as LaTeX or PDF for easy sharing and printing.

---

## 📑 Table of Contents
- [Features](#features)
- [Getting Started](#getting-started)
  - [Prerequisites](#prerequisites)
  - [Installation](#installation)
- [Development](#development)
- [Deployment](#-deployment)
- [Architecture Overview](#architecture-overview)
- [Contributing](#contributing)
- [License](#license)  

---

## 🚀 Getting Started
### Prerequisites

- [Node.js](https://nodejs.org/) (Version 16 or higher)
- [Composer](https://getcomposer.org/) (Version 2.0 or higher)
- [Symfony CLI](https://symfony.com/download)
- [PHP](https://www.php.net/) (Version 7.4 or higher)
- [OpenAI API Access](https://platform.openai.com/signup/) (for generating recommendations and using AI features)

### Installation

 **1. Clone the repository:**
   ```bash
   git clone https://github.com/ISG-Siegen/checky.git 
   ```

 **2. Install dependencies:**
   ```bash
   npm install
   cd ~/Checky/checklist-backend
   composer install
   ```

**3. Environment setup:**

Create a `.env.local` file in the root directory with the following content:

```env
APP_SECRET=<your_app_secret>

DATABASE_URL=mysql://username:password@db_host:3306/database_name?serverVersion=5.7&charset=utf8mb4

OPENAI_API_KEY="<Your OpenAI API Key>"
```

- Replace `<your_app_secret>` with a secure, randomly generated string.
- Replace `username`, `password`, `localhost`, and `database_name` in `DATABASE_URL` with your MySQL configuration details:
  - `username`: Your database username.
  - `password`: Your database password.
  - `db_host`: Your database host (e.g., `127.0.0.1`).
  - `database_name`: The name of your database.
- Replace `<Your OpenAI API Key>` with the API key from your OpenAI account.

For more information, refer to the official [Symfony Doctrine Documentation](https://symfony.com/doc/current/doctrine.html).


## 🛠️ Development

### Development server

In the development environment, we use two servers:

1. **Frontend Server**
   - Run `ng serve` to start the Angular development server. Navigate to `http://localhost:4200/`. The application will automatically reload if you make changes to the source files.

2. **Backend Server**
   - Run `symfony server:start` to start the Symfony backend server. The backend server handles API requests and runs on a different port (default: `http://127.0.0.1:8000`).

Ensure both servers are running simultaneously for full functionality.


## 📦 Deployment
### Prepare Files

Run `ng build` to build the Angular project. The build artifacts will be stored in the `dist/` directory.

Next, follow the official [Symfony Deployment Guide](https://symfony.com/doc/current/deployment.html) to prepare the backend for production.

Once both the frontend and backend are built, you can serve the files using a web server.

#### Example for Apache

If you're using Apache, you can configure it with the following `.htaccess` example:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On

    RewriteRule ^api(/.*)?$ /public/index.php [QSA,END]

    RewriteRule ^/?$ /app/index.html [QSA,END]
    RewriteRule ^(.*)?$ /app/$1 [QSA,END]

    ErrorDocument 404 /app/index.html
</IfModule>
```
📌 *Note:* This setup expects the Symfony app in the root directory and the Angular build files in the `/app` directory.


## 🏛️ Architecture Overview
### Frontend
Built with Angular and PrimeNG for an interactive UI.

### Backend
Powered by Symfony for robust API services and database management.

### Database
Utilizes Doctrine ORM with a MySQL database.


## 🤝 Contributing

We welcome contributions to enhance Checky's functionality and expand its checklist archive.

1. **Fork the repository.**

2. **Create a new branch:**
   ```bash
   git checkout -b feature-branch
   ```
3. **Make your changes.**
4. **Commit your changes:**
   ```bash
   git commit -m 'Add new feature'
   ```
5. **Push to the branch:**
   ```bash
   git push origin feature-branch
   ```
6. **Open a pull request.**

## 📄 License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more information.

## ✉️ Contact

For support or inquiries, please visit our [Contact Page](https://isg.beel.org/contact/).

