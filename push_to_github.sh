#!/bin/bash

echo "Adding files..."
git add .

echo "Enter commit message:"
read message

if [ -z "$message" ]; then
    message="Update project files"
fi

echo "Committing changes..."
git commit -m "$message"

echo "Pushing to GitHub..."
git push origin main

echo "Done."