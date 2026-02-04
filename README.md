# Attendance Service

A minimal Symfony 8 application providing a framework for a **classroom morning attendance** service.

## Structure

- **`src/Attendance/`** – Domain and application layer:
  - **Model** – `AttendanceRecord`, `AttendanceSubmission` (value objects / DTOs).
  - **Repository** – `AttendanceRepositoryInterface` (persistence abstraction).
  - **Queue** – `QueueClientInterface` (async job enqueue).
  - **Service** – `AttendanceSubmissionService` (validate + enqueue), `AttendanceJobHandler` (process one job, persist via repository).

No concrete implementations of the repository or queue are included; those would be added per environment.

## Running tests

```bash
composer install
composer test
```

Or directly:

```bash
./bin/phpunit
```
