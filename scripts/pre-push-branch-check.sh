#!/bin/bash
# Git pre-push hook for validating branch names.

BRANCH_REGEX="^(task|bug|feature)/[0-9]+-[a-zA-Z0-9-]+$"

# ANSI Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
NC='\033[0m' # No Color (reset)

# Get the name of the current branch
CURRENT_BRANCH=$(git symbolic-ref --short HEAD)

# Exclude main branch from validation
EXCLUDED_BRANCHES=("main")

# Check if the current branch is one of the excluded branches
for EXCLUDED in "${EXCLUDED_BRANCHES[@]}"; do
    if [[ "$CURRENT_BRANCH" == "$EXCLUDED" ]]; then
        echo -e "${GREEN}Skipping branch name validation for protected branch: $CURRENT_BRANCH${NC}"
        exit 0
    fi
done

# Validate the current branch name against the regex
if [[ "$CURRENT_BRANCH" =~ $BRANCH_REGEX ]]; then
    echo -e "${GREEN}Branch name '$CURRENT_BRANCH' is valid. Push allowed.${NC}"
    exit 0
else
    echo -e "${RED}--------------------------------------------------------${NC}"
    echo -e "${RED}❌ PUSH FAILED: Invalid branch name format.${NC}"
    echo -e "${RED}Branch: $CURRENT_BRANCH${NC}"
    echo -e "${RED}--------------------------------------------------------${NC}"
    echo "Branch naming rules are:"
    echo "1. Must start with 'task/', 'bug/', or 'feature/'"
    echo "2. Must include an issue number (digits) followed by a dash."
    echo "3. Must end with a descriptive slug (letters, numbers, dashes)."
    echo ""
    echo "Examples of valid names:"
    echo " - feature/123-add-user-profile"
    echo " - bug/45-fix-login-redirect"
    echo " - task/789-update-composer-deps"
    echo -e "${RED}--------------------------------------------------------${NC}"
    # Exit with a non-zero status to abort the push
    exit 1
fi