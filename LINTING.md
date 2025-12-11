# Code Quality & Linting

This project uses automated linting and code quality tools for both PHP and JavaScript/Vue.js.

## Quick Start

### View all available commands
```bash
make help
```

### Run all linters
```bash
make lint
```

### Auto-fix all issues
```bash
make fix
```

## Linting Tools

### PHP Linting
- **PHP CS Fixer**: Code style fixer following PSR-12 standards
- **PHPStan (with Larastan)**: Static analysis tool for Laravel

### JavaScript/Vue Linting
- **ESLint**: JavaScript linter with Vue plugin
- **Prettier**: Code formatter

## Makefile Commands

### Linting
- `make lint` - Run all linters (PHP + JS)
- `make lint-php` - Run PHP linters only
- `make lint-js` - Run JavaScript/Vue linters only

### Fixing
- `make fix` - Auto-fix all issues
- `make fix-php` - Auto-fix PHP code style
- `make fix-js` - Auto-fix JavaScript/Vue issues
- `make format` - Format code with Prettier

### Development
- `make install` - Install all dependencies
- `make build` - Build production assets
- `make dev` - Start development servers (PHP + Vite)
- `make clean` - Clean cache files

## NPM Scripts

```bash
npm run lint          # Run ESLint
npm run lint:fix      # Run ESLint with auto-fix
npm run format        # Format with Prettier
```

## Composer Scripts

```bash
vendor/bin/php-cs-fixer fix              # Fix PHP code style
vendor/bin/php-cs-fixer fix --dry-run    # Check without fixing
vendor/bin/phpstan analyse               # Run static analysis
```

## GitHub Actions

The project includes a CI/CD pipeline (`.github/workflows/code-quality.yml`) that:
- ✅ Runs PHP linters on every push/PR
- ✅ Runs JavaScript/Vue linters
- ✅ Checks code formatting
- ✅ Builds production assets
- ✅ Runs on `main` and `develop` branches

## Configuration Files

- `.php-cs-fixer.php` - PHP CS Fixer configuration
- `phpstan.neon` - PHPStan configuration
- `eslint.config.js` - ESLint configuration (flat config)
- `.prettierrc` - Prettier configuration

## Pre-commit Hook (Optional)

To automatically lint before commits, add to `.git/hooks/pre-commit`:

```bash
#!/bin/bash
make lint
```

Make it executable:
```bash
chmod +x .git/hooks/pre-commit
```
