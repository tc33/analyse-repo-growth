#!/usr/bin/env bash

repo="git@github.com:WordPress/WordPress.git"
ignore_dirs=".git|wp-content/plugins|wp-content/themes|wp-includes/js/jquery|wp-includes/js/tinymce|wp-includes/js/imgareaselect|wp-includes/js/crop|wp-includes/js/jcrop|wp-includes/js/mediaelement|wp-includes/js/plupload|wp-includes/js/swfupload|wp-includes/js/thickbox|wp-includes/js/dist/vendor"
ignore_files=".min.js|.min.css"
output_dir="./wp-output"

# Run the main script on the WordPress repository
./run.sh $repo $ignore_dirs $ignore_files $output_dir