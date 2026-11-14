# Lab Activity 11 – Barangay Bulletin (Purok Ramirez)

Andrey Ramirez · 4-334 Full-Stack Web Development

My Lab 10 bulletin for Barangay Matina Crossing, now with author names and tags on every post, comments from logged-in residents, and a "New post" form for my Kagawad. All forms are validated with Form Requests. Login is still the simple manual login from Lab 9 (Breeze is next week).

## Logins (seeded)

| Who | Email | Password | Can do |
|---|---|---|---|
| Lola Ramirez (resident) | lola.ramirez@bulletin.test | bulletin2026 | comment |
| Kagawad Andrey Ramirez | kagawad.ramirez@bulletin.test | bulletin2026 | comment + write posts |

There are also 8 random residents from `UserFactory` (password `password`). Passwords are hashed with `Hash::make()`.

## My personalized data

| Item | Value |
|---|---|
| Site title | Barangay Bulletin – Purok Ramirez |
| My author | Kagawad Andrey Ramirez, now linked to his login account (`authors.user_id`) |
| Posts per page | 7 (Ramirez = 7 letters) |
| Categories | Announcement, Health, Peace & Order, Ayuda, Livelihood |
| Pinned post | "Purok Ramirez Clean-up Drive", May 20, 2026 (my birthday), always tagged Purok Ramirez + Urgent, with 3 seeded comments (one from Lola) |
| Tags | **Purok Ramirez, Urgent, Libre, Schedule, Bayanihan** (Bayanihan is mine, for community work like clean-ups and tree planting) |
| Short comment error | "Purok Ramirez comments need at least 3 characters." |

## Relationships I used and why

| Link | Type | Why |
|---|---|---|
| User → Author | `hasOne` / `belongsTo` | `authors.user_id` is unique, so one account has at most one author profile. Residents have none, that's how `StorePostRequest` knows who can post. |
| Author → Post | `hasMany` / `belongsTo` | one author writes many posts, `posts.author_id` is on the post side. |
| Post → Comment | `hasMany` / `belongsTo` | a post has many comments, `comments.post_id`. |
| User → Comment | `hasMany` / `belongsTo` | a resident can write many comments, `comments.user_id`. |
| Post ↔ Tag | `belongsToMany` (both ways) | a post has many tags and a tag is on many posts, so it needs the `post_tag` pivot (composite key so the same tag can't be added twice). |
| Author → Comment | `hasManyThrough` (via Post) | comments don't have an `author_id`, so to count "comments received" we hop Author → posts → comments. |

## What's new in Lab 11

- **Migrations** (Lab 10 ones not touched): `create_comments_table`, `create_tags_table`, `create_post_tag_table`, `add_user_id_to_authors_table`. All have a `down()`.
- **Seeders**: `UserSeeder`, `TagSeeder` (tags attached with `attach()`, pinned post with `sync()`), `CommentSeeder` (0–5 per published post using `CommentFactory`). Order in `DatabaseSeeder`: Author, Post, User, Tag, Comment.
- **No N+1**: `Model::preventLazyLoading()` is on in `AppServiceProvider`. The index uses `with(['author', 'tags'])->withCount('comments')`, the show page uses `load()` with `comments.user`.
- **`?tag=` filter** on `/posts` with `whereHas('tags', ...)`. Clicking a tag badge filters by it.
- **Form Requests**: `StoreCommentRequest` (logged in, body 3–1000 chars) and `StorePostRequest` (only users with an author profile, residents get 403). Controllers only use `$request->validated()`.
- **Pages**: `/posts/create` (new post form), `/authors/{author}` (author's published posts + "N comments received").

## Routes

| Method | URI | Name | Middleware |
|---|---|---|---|
| GET | /login | login | guest |
| POST | /login | | guest |
| POST | /logout | logout | auth |
| GET | /posts | posts.index | |
| GET | /posts/create | posts.create | auth |
| POST | /posts | posts.store | auth |
| POST | /posts/{post}/comments | comments.store | auth |
| GET | /posts/{id} | posts.show | |
| GET | /authors/{author} | authors.show | |

`/posts/create` is registered before `/posts/{id}` so "create" isn't read as an id.

## Checking in tinker

```
php artisan tinker
>>> App\Models\User::where('email', 'like', 'kagawad.%')->first()->author->name   // "Kagawad Andrey Ramirez"
>>> App\Models\Author::first()->comments()->count()                             // hasManyThrough
>>> App\Models\Post::where('is_pinned', true)->first()->tags->pluck('name')     // ["Purok Ramirez", "Urgent"]
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
