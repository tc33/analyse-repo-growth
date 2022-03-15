<?php

namespace TC33\AnalyseRepoGrowth;

interface Report {

	const REPORTS_DIR = __DIR__ . '/../reports/';

	public function generate(array $data);
}