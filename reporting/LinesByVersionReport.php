<?php

use TC33\AnalyseRepoGrowth\Report;

class LinesByVersionReport implements Report {

	public function generate(array $data) {
		$rows      = [];
		$languages = $this->languages($data);

		foreach ($data as $versionNumber => $versionData) {
			$row = [$versionNumber];
			foreach ($languages as $language) {
				$row[] = $versionData[$language]['code'] ?? 0;
			}
			$rows[] = $row;
		}

		$csv = new CSVGenerator(Report::REPORTS_DIR . 'lines-by-version.csv');
		$csv->setHeadings(array_merge(['Version'], $languages));
		$csv->setRows($rows);
		$csv->save();
	}

	private function languages(array $data): array {
		$languages = [];

		foreach ($data as $versionData) {
			$languages = array_unique(array_merge($languages, array_keys($versionData)));
		}

		return $languages;
	}
}