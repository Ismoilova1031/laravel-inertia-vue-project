# Study Planner

Online course platform (teacher and student sides) built with Laravel 13, Inertia, Vue 3 and Vuetify.
The backend follows DDD and Clean Architecture.

## Architecture

```
Http ──► Application ──► Domain ◄── Infrastructure
```

| Layer | Responsibility |
|---|---|
| `Domain` | Entities, value objects, domain services, repository interfaces. No Laravel. |
| `Application` | One use case per operation (command in, result out). Transactions for multi-aggregate changes. |
| `Infrastructure` | Eloquent models, repositories, file storage, password hasher. |
| `Http` | Controllers, form requests, resources. Thin. |

Key conventions
- Entities always have an id. Creation data lives in `New*` classes; repositories expose `create(New*)` and `save()` (update only).
- Repositories contain simple selects only; filtering and calculation happen in use cases.
- Business rules live in the domain and are unit-tested without a database.

## Domain rules

- **Course**: new courses are Draft; Published needs at least one lesson; only Draft can be deleted.
- **Lesson**: order is always 1..n; content depends on type (video, text, task).
- **Enrollment**: only Published courses; passive students keep results but cannot act.
- **Tasks**: quiz (auto-graded, open answers graded by teacher), file (accept/reject), discussion. Max 3 attempts.
- **Completion**: all lessons done. Certificate grade (S, A, B, C, D) comes from the best quiz scores.
- **Review**: only after completing the course.

## Run

```bash
composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate:fresh --seed
php artisan wayfinder:generate
npm run dev & php artisan serve
```

Demo logins (password `password`): `teacher`, `ali`, `sara`, `vali`, `mina`.

## Test

```bash
php artisan test      # domain unit tests, use case and HTTP feature tests
npx vue-tsc --noEmit
```