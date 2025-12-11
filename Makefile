.PHONY: help lint lint-php lint-js fix fix-php fix-js format test install build clean

# Colors for output
BLUE := \033[0;34m
GREEN := \033[0;32m
YELLOW := \033[0;33m
NC := \033[0m # No Color

## help: Display this help message
help:
	@echo "${BLUE}Available commands:${NC}"
	@echo ""
	@echo "${GREEN}Linting:${NC}"
	@echo "  make lint           - Run all linters (PHP + JS)"
	@echo "  make lint-php       - Run PHP linters (CS Fixer + PHPStan)"
	@echo "  make lint-js        - Run JavaScript/Vue linters (ESLint)"
	@echo ""
	@echo "${GREEN}Fixing:${NC}"
	@echo "  make fix            - Auto-fix all issues (PHP + JS)"
	@echo "  make fix-php        - Auto-fix PHP code style issues"
	@echo "  make fix-js         - Auto-fix JavaScript/Vue issues"
	@echo "  make format         - Format code with Prettier"
	@echo ""
	@echo "${GREEN}Build:${NC}"
	@echo "  make install        - Install all dependencies (Composer + NPM)"
	@echo "  make build          - Build production assets"
	@echo "  make dev            - Start development server"
	@echo ""
	@echo "${GREEN}Other:${NC}"
	@echo "  make clean          - Clean cache and temporary files"
	@echo "  make test           - Run tests (when implemented)"

## lint: Run all linters
lint: lint-php lint-js
	@echo "${GREEN}✓ All linting completed${NC}"

## lint-php: Run PHP linters
lint-php:
	@echo "${BLUE}Running PHP CS Fixer...${NC}"
	@vendor/bin/php-cs-fixer fix --dry-run --diff --verbose
	@echo "${BLUE}Running PHPStan...${NC}"
	@vendor/bin/phpstan analyse --memory-limit=2G

## lint-js: Run JavaScript/Vue linters
lint-js:
	@echo "${BLUE}Running ESLint...${NC}"
	@npm run lint

## fix: Auto-fix all issues
fix: fix-php fix-js format
	@echo "${GREEN}✓ All fixes applied${NC}"

## fix-php: Auto-fix PHP code style issues
fix-php:
	@echo "${BLUE}Fixing PHP code style...${NC}"
	@vendor/bin/php-cs-fixer fix --verbose

## fix-js: Auto-fix JavaScript/Vue issues
fix-js:
	@echo "${BLUE}Fixing JavaScript/Vue code...${NC}"
	@npm run lint:fix

## format: Format code with Prettier
format:
	@echo "${BLUE}Formatting code with Prettier...${NC}"
	@npm run format

## install: Install all dependencies
install:
	@echo "${BLUE}Installing Composer dependencies...${NC}"
	@composer install
	@echo "${BLUE}Installing NPM dependencies...${NC}"
	@npm install
	@echo "${GREEN}✓ All dependencies installed${NC}"

## build: Build production assets
build:
	@echo "${BLUE}Building production assets...${NC}"
	@npm run build
	@echo "${GREEN}✓ Assets built successfully${NC}"

## dev: Start development server
dev:
	@echo "${BLUE}Starting development servers...${NC}"
	@npx concurrently -n "PHP,Vite" -c "blue,magenta" \
		"php artisan serve" \
		"npm run dev"

## clean: Clean cache and temporary files
clean:
	@echo "${BLUE}Cleaning cache and temporary files...${NC}"
	@rm -rf .php-cs-fixer.cache
	@rm -rf node_modules/.cache
	@php artisan cache:clear
	@php artisan config:clear
	@php artisan view:clear
	@php artisan route:clear
	@echo "${GREEN}✓ Cache cleaned${NC}"

## test: Run tests
test:
	@echo "${BLUE}Running tests...${NC}"
	@php artisan test
