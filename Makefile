# Define the target directory where the zip files will be created
TARGET_DIR := builds

# List of all subdirectories, excluding the target directory
SUBDIRS := $(filter-out $(TARGET_DIR)/, $(wildcard */))

# Remove trailing slash from directory names
DIR_NAMES := $(patsubst %/,%,$(SUBDIRS))

# Create a list of target zip files with the same name as the directories
ZIP_FILES := $(addsuffix .zip, $(addprefix $(TARGET_DIR)/, $(DIR_NAMES)))

# Default target to create all zip files
all: $(ZIP_FILES)

# Rule to create a zip file for each subdirectory
$(ZIP_FILES): $(TARGET_DIR)/%.zip : %
	@mkdir -p $(@D)
	zip -r "$@" "$<"

# Clean up targets
clean:
	rm -rf $(TARGET_DIR)

# Define 'build' as an alias for 'all'
build: all

##@ General

.PHONY: help
help: ## Display this help.
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n"} /^[a-zA-Z_0-9-]+:.*?##/ { printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)


##@ Development

##@ Build
.PHONY: build clean

.PHONY: full-build
full-build:
	make clean
	make build


##@ Deployment
