#!/usr/bin/env bash
[ -d "./wordpress" ] && printf "Removing existing wordpress directory\n"
rm -rf wordpress
[ -d "./data" ] && printf "Removing existing data directory\n"
rm -rf data

# Clone WordPress repo
git clone git@github.com:WordPress/WordPress.git wordpress
cd wordpress

# Get list of major versions matching pattern x.x
versions=( $(git tag -l "[0-9].[0-9]") )

# Checkout each version and count LOC
for i in "${versions[@]}"
do
  printf "Checking out version $i\n"
  git checkout $i

  printf "Counting lines of code in $i\n"

  # Perform count excluding default plugins and themes
  cloc --exclude-dir=.git --fullpath --not-match-d="wp-content/(plugins|themes)" --hide-rate --csv --report-file=../data/$i.csv .
done

# Clean up
cd ..
printf "Deleting WordPress\n"
rm -rf wordpress
