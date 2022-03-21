#!/usr/bin/env bash

# Read arguments
ignore_dirs=$2
ignore_files=$3
output_dir=${4:-"./output"}
version_format=${5:-"[0-9].[0-9]"}

printf "Removing any existing temporary files\n"

[ -d "$output_dir/repo" ] && rm -rf $output_dir/repo
[ -d "$output_dir/data" ] && rm -rf $output_dir/data

# Create empty versions CSV with headers
mkdir -p $output_dir/data
echo "version,date" >> $output_dir/data/versions.csv

# Clone repository
git clone $1 $output_dir/repo
#cd $output_dir/repo

# Get list of versions matching version format
versions=( $(git -C $output_dir/repo tag -l $version_format) )

# Checkout each version and count LOC
for version in "${versions[@]}"
do
  printf "Checking out version $version\n"
  git -C $output_dir/repo checkout $version

  printf "Counting lines of code in $version\n"

  # Perform count excluding default plugins and themes
  cloc --fullpath --not-match-d="($ignore_dirs)" --not-match-f="($ignore_files)" --hide-rate --csv --report-file=$output_dir/data/counts/$version.csv $output_dir/repo

  # Save the date of this version to the versions CSV
  printf "Saving release date of $version\n"
  release_date=$(git -C $output_dir/repo show -s --format=%ci)
  echo "$version,$release_date" >> $output_dir/data/versions.csv
done

# Generate the reports
printf "Generating reports\n"
php reporting/generate-reports.php $output_dir

# Clean up
printf "Removing temporary files\n"
cd ..
[ -d "$output_dir/repo" ] && rm -rf $output_dir/repo
[ -d "$output_dir/data" ] && rm -rf $output_dir/data