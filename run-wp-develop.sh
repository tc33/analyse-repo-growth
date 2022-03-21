#!/usr/bin/env bash

repo="git@github.com:WordPress/wordpress-develop.git"
ignore_dirs=".git"
ignore_files=".min.js|.min.css"
output_dir="./wp-develop-output"
version_format="[0-9].[0-9].0"

# Run the main script on the WordPress repository
./run.sh $repo $ignore_dirs $ignore_files $output_dir $version_format