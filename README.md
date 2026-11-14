# Lab Activity 10 – Barangay Bulletin (Purok Ramirez)

Andrey Ramirez · 4-334 Full-Stack Web Development

A small blog for Barangay Matina Crossing Hall where barangay officials post announcements and residents can browse, search and filter them. All pages read from the database using Eloquent (Laravel 12 + SQLite + Tailwind).

## My personalized data

| Item | Value |
|---|---|
| Site title | Barangay Bulletin – Purok Ramirez |
| My author | Kagawad Andrey Ramirez (kagawad.ramirez@bulletin.test), made with `Author::create()` in `AuthorSeeder` |
| Posts per page | **7** – "Ramirez" has 7 letters (R-A-M-I-R-E-Z), which is already at least 6 |
| Custom category | **Livelihood** – for skills training, job fairs and small-business programs in the barangay |
| Pinned post | "Purok Ramirez Clean-up Drive", published **May 20, 2026** (my birthday) |

Categories: Announcement, Health, Peace & Order, Ayuda, Livelihood.

## What's inside

- `database/migrations` – `create_authors_table`, `create_posts_table`, and a separate `add_views_to_posts_table` that adds the `views` column with `Schema::table` (I did not edit the posts migration).
- `app/Models` – `Author` and `Post` with `$fillable`, and `Post` casts the booleans and `published_at`.
- `database/factories` – `AuthorFactory` and `PostFactory` (with a `draft()` state).
- `database/seeders` – `AuthorSeeder` (my Kagawad + 4 fake authors), `PostSeeder` (40 published, 10 drafts, my pinned post). `DatabaseSeeder` calls them in that order.
- `app/Http/Controllers/PostController.php` – `index` (published only, pinned first, `?q=` and `?category=` filters with `when()`, paginated) and `show` (`findOrFail`, 404 for drafts, adds 1 to views then `save()`).
- `resources/views` – `layouts/app.blade.php`, `posts/index.blade.php`, `posts/show.blade.php`.
- `docs/tinker.png` – tinker screenshot of `find()` → change title → `save()`.

After `php artisan migrate:fresh --seed`:

```
Author::count()                          // 5
Post::count()                            // 51
Post::where('is_published', false)->count() // 10
```

## How to run

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm install
npm run dev
php artisan serve
```

If `database/database.sqlite` is missing, create it first (PowerShell: `New-Item database/database.sqlite`). Then open http://127.0.0.1:8000 (it redirects to `/posts`).
