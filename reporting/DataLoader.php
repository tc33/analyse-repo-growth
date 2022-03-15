<?php

namespace TC33\AnalyseRepoGrowth;

class DataLoader {

	const COL_LANGUAGE = 'language';

	private array $data;

	public function __construct(
		private string $directoryPath
	) {
	}

	private function load() {
		// Fetch list of files in data directory
		$files = scandir($this->directoryPath);

		// Remove hidden files
		$files = preg_grep('/^([^.])/', $files);

		// Load each file into data property
		foreach ($files as $file) {
			$this->loadFile($file);
		}
	}

	private function loadFile($fileName) {
		$version = pathinfo($fileName, PATHINFO_FILENAME);

		$data = [];

		$file = fopen($this->directoryPath . DIRECTORY_SEPARATOR . $fileName, 'r');
		if (feof($file)) {
			// Empty
			return;
		}

		// Assume the first row contains the column headers
		$columns       = fgetcsv($file, 0, ',');
		$columnsByName = array_flip($columns);

		while (! feof($file)) {
			$row = fgetcsv($file, 0, ',');

			$languageColumn = $columnsByName[self::COL_LANGUAGE];
			$language       = $row[$languageColumn] ?? false;
			$languageData   = [];

			// Compile the data by column name - exclude language
			foreach ($row as $index => $item) {
				if ($index != $languageColumn) {
					$languageData[$columns[$index]] = $item;
				}
			}

			// Index the data by language
			$data[$language] = $languageData;
		}

		$this->data[$version] = $data;
	}

	public function getData(): array {
		if ($this->data == null) {
			$this->load();
		}

		return $this->data;
	}
}