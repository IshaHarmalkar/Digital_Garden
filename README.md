# 🌻 Curated

A Readwise equivalent tool for resurfacing, and revisiting content from **Notion** and **Pinterest**. It also had a mood tracker using the feelings wheel method, journal note and a gratitude list as I find this practices pretty helpful.

---

## 🌻 Overview

**Curated** lets you sync your notion pages and saved pins from Pinterest and then curates a newsletter sent to you weekly. Beside this central feature, it allows you to process and track your days with a mood tracker based on the feelings wheel concept, add a journal note, and wrap your day with gratitude with the gratitude list feature.

- **Laravel** (API + backend logic)
- **Quasar / Vue** (frontend UI)
- **MySQL** (database)

The problem I tried to solve was _consumtion overload_: we save things with the intention of revisting but we rarely do revisit them and instead are stuck in the loop of overconsumtion and droom scrolling. Curated ensures your curated notes find you in time, and helps you towards building a life with intention. Did you know, we also have a tagline, a core message -> "FROM INTENTION TO ACTION."

---

## 🌻 Features

- Import pages from Notion, SYNC
- Pull pins / images / links from Pinterest boards, SYNC
- Does the syncing periodically, each week before we curate the newsletter
- The curation algo for newsletter is based on the idea of the queue data strucute. To put it simply, new content is added to the end of queue, the newsletter created from queue front.
- Cooles thing is that we don't really need to built a raw queue. A db works fine, if you think about it. i.e timestamps.
- Track mood, journal notes, gratitude.
- Minimal, clean UI powered by Quasar

---

## 🌻 Tech Stack

- **Backend**: Laravel -> cron jobs, elequent relations.
- **Frontend**: Quasar Framework (Vue.js)
- **Database**: MySQL

---

## 🌻 Project Structure

```
curated/
├── backend/ (Laravel)
│   ├── app/
│   ├── routes/
│   ├── database/
│   └── ...
├── frontend/ (Quasar)
│   ├── src/
│   ├── public/
│   └── ...
└── README.md
```

---

## 🌻 Installation & Setup

### **Clone the project**

```sh
git clone <your-repo-url>
cd curated
```

---

## 🌻 Backend Setup (Laravel)

```sh
cd backend
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

---

## 🌻 Frontend Setup (Quasar)

```sh
cd frontend
npm install
quasar dev
```

---

## 🌻 Run the Full Project

Backend:

```sh
php artisan serve
```

Frontend:

```sh
quasar dev
```

---

## 🌻 Build Commands

### Frontend (Quasar)

```sh
quasar build
```

### Backend

```sh
php artisan optimize
```

---

---

## 🌻 Issues

Feel free to open an issue for bugs, feature requests, or improvements.

---

## 🌻 Screenshots

### 🌻 Home Page

![Newsletter](screenshots/home.png)

### 🌻 Daily Digest

![Dashboard](screenshots/digest.png)

---
