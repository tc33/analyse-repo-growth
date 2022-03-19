#!/usr/bin/env bash
[ -d "./wordpress" ] && printf "Removing existing wordpress directory\n"
rm -rf wordpress
[ -d "./data" ] && printf "Removing existing data directory\n"
rm -rf data
[ -d "./versions.csv" ] && printf "Removing existing versions file\n"
rm versions.csv

# Clone WordPress repo
git clone git@github.com:WordPress/WordPress.git wordpress
cd wordpress

# Create empty versions CSV with headers
echo "version,date" >> ../versions.csv

# Get list of major versions matching pattern x.x
versions=( $(git tag -l "[0-9].[0-9]") )

ignore_dirs=".git|wp-content/plugins|wp-content/themes|wp-includes/js/jquery|wp-includes/js/tinymce|wp-includes/js/dist/vendor"
ignore_files=".min.js|.min.css"

# Checkout each version and count LOC
for i in "${versions[@]}"
do
  printf "Checking out version $i\n"
  git checkout $i

  printf "Counting lines of code in $i\n"

  # Perform count excluding default plugins and themes
  cloc --fullpath --not-match-d="($ignore_dirs)" --not-match-f="($ignore_files)" --hide-rate --csv --report-file=../data/$i.csv .

  # Get the date of this version
  printf "Saving release date of $i\n"
  release_date=$(git show -s --format=%ci)
  echo "$i,$release_date" >> ../versions.csv
done

# Generate the reports
printf "Generating reports\n"
cd ../reporting
php generate-reports.php

# Clean up
cd ..
printf "Deleting WordPress\n"
#rm -rf wordpress
printf "Deleting data files\n"
#rm -rf data
#printf "Deleting versions file\n"
#rm versions.csv