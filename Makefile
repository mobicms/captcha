# Use bash for command execution for its advanced features like arrays and functions.
SHELL := /bin/bash

# --- Color Codes ---
COLOR_GREEN := \e[1;32m
COLOR_PURPLE := \e[0;95m
COLOR_RESET := \e[0m

# Default target to run when 'make' is called without arguments.
.DEFAULT_GOAL := help

help: ## Show this help message
	@echo "============================================================"
	@echo "Docker Environment"
	@echo "------------------------------------------------------------"
#	@echo ""
	@echo "Core Commands:"
	@echo -e "     $(COLOR_GREEN)make up$(COLOR_RESET) - Start all services"
	@echo -e "   $(COLOR_GREEN)make stop$(COLOR_RESET) - Stop all containers"
	@echo -e "   $(COLOR_GREEN)make down$(COLOR_RESET) - Stop and remove all containers"
	@echo -e "  $(COLOR_GREEN)make build$(COLOR_RESET) - Build or rebuild Docker images"
	@echo ""
	@echo "Links:"
	@echo -e "   $(COLOR_PURPLE)DEMO page$(COLOR_RESET) - http://localhost"
	@echo ""

# --- Project Lifecycle Targets ---
build: ## Build images. Use 'options' for flags (e.g., --no-cache)
	docker compose build

up: ## Start services
	@echo "Starting services..."
	docker compose up -d

stop: ## Stop services
	@echo "Stopping all containers..."
	docker compose stop

down: ## Stop and remove all containers
	@echo "Stopping and removal all containers..."
	docker compose down
