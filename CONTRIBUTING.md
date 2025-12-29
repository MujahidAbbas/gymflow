# Contributing to GymFlow

Thank you for your interest in contributing to GymFlow! This document provides guidelines and information for contributors.

## How to Contribute

### Reporting Bugs

1. Check if the issue already exists in [GitHub Issues](https://github.com/MujahidAbbas/gymflow/issues)
2. If not, create a new issue with:
   - Clear, descriptive title
   - Steps to reproduce
   - Expected vs actual behavior
   - Environment details (PHP, Laravel, OS)

### Suggesting Features

Open a feature request issue with:
- Description of the feature
- Use case / problem it solves
- Proposed implementation (optional)

### Pull Requests

1. **Fork** the repository
2. **Create a branch** from `main`:
   ```bash
   git checkout -b feature/your-feature-name
   ```
3. **Make your changes** following our coding standards
4. **Write tests** for new functionality
5. **Run tests** to ensure nothing is broken:
   ```bash
   php artisan test
   ```
6. **Commit** with clear messages:
   ```bash
   git commit -m "Add: brief description of change"
   ```
7. **Push** to your fork and **open a Pull Request**

## Coding Standards

- Follow **PSR-12** coding style
- Use **Laravel Pint** for code formatting:
  ```bash
  ./vendor/bin/pint
  ```
- Write meaningful variable and function names
- Add PHPDoc blocks for public methods
- Keep methods focused and small

## Testing

- Write tests for new features
- Ensure existing tests pass
- Use feature tests for HTTP endpoints
- Use unit tests for isolated logic

## Commit Message Format

Use conventional commit prefixes:
- `Add:` New feature
- `Fix:` Bug fix
- `Update:` Change to existing feature
- `Remove:` Removing code/feature
- `Docs:` Documentation only
- `Test:` Adding or updating tests
- `Refactor:` Code restructuring

## Questions?

Open a discussion or issue on GitHub.

---

Thank you for contributing! 🏋️
