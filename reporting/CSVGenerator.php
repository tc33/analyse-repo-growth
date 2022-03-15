<?php

class CSVGenerator {

	private $file;
	private array $headings = [];
	private array $rows = [];

	public function __construct(private string $filePath) {
	}

	public function setHeadings(array $headings) {
		$this->headings = $headings;
	}

	public function setRows(array $rows) {
		$this->rows = $rows;
	}

	public function save() {
		$this->file = fopen($this->filePath, 'w');

		$this->outputRow($this->headings);

		foreach ($this->rows as $row) {
			$this->outputRow($row);
		}

		fclose($this->file);
	}

	protected function outputRow($row) {
		fputcsv($this->file, $row);
	}
}