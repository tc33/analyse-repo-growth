#!/usr/bin/env bash

printf "Removing any existing temporary files\n"

[ -d "./repo" ] && rm -rf repo
[ -d "./data" ] && rm -rf data

# Clone repository
git clone git@github.com:WordPress/WordPress.git repo
cd repo

# Create empty versions CSV with headers
mkdir ../data
echo "version,date" >> ../data/versions.csv

# Get list of major versions matching pattern x.x
versions=( $(git tag -l "[0-9].[0-9]") )

# Define files and directories to exclude from line counts
ignore_dirs=".git|wp-content/plugins|wp-content/themes|wp-includes/js/jquery|wp-includes/js/tinymce|wp-includes/js/imgareaselect|wp-includes/js/crop|wp-includes/js/jcrop|wp-includes/js/mediaelement|wp-includes/js/plupload|wp-includes/js/swfupload|wp-includes/js/thickbox|wp-includes/js/dist/vendor"
ignore_files=".min.js|.min.css"

# Checkout each version and count LOC
for i in "${versions[@]}"
do
  printf "Checking out version $i\n"
  git checkout $i

  printf "Counting lines of code in $i\n"

  # Perform count excluding default plugins and themes
  cloc --fullpath --not-match-d="($ignore_dirs)" --not-match-f="($ignore_files)" --hide-rate --csv --report-file=../data/counts/$i.csv .

  # Save the date of this version to the versions CSV
  printf "Saving release date of $i\n"
  release_date=$(git show -s --format=%ci)
  echo "$i,$release_date" >> ../data/versions.csv
done

# Generate the reports
printf "Generating reports\n"
cd ../reporting
php generate-reports.php

# Clean up
printf "Removing temporary files\n"
cd ..
[ -d "./repo" ] && rm -rf repo
[ -d "./data" ] && rm -rf data