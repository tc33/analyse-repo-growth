<?php

use TC33\AnalyseRepoGrowth\DataLoader;
use TC33\AnalyseRepoGrowth\LinesByDateChart;
use TC33\AnalyseRepoGrowth\LinesByVersionChart;
use TC33\AnalyseRepoGrowth\LinesByLanguageChart;
use TC33\AnalyseRepoGrowth\Report;

include __DIR__ . '/DataLoader.php';
include __DIR__ . '/Report.php';
include __DIR__ . '/LinesByVersionReport.php';
include __DIR__ . '/LinesByVersionChart.php';
include __DIR__ . '/LinesByLanguageChart.php';
include __DIR__ . '/LinesByDateChart.php';
include __DIR__ . '/CSVGenerator.php';

$dataLoader = new DataLoader(__DIR__ . '/../data/counts/');
$data       = $dataLoader->getData();

$reports = [LinesByVersionReport::class, LinesByVersionChart::class, LinesByLanguageChart::class, LinesByDateChart::class];

if (! file_exists(Report::REPORTS_DIR)) {
	mkdir(Report::REPORTS_DIR);
}

foreach ($reports as $reportType) {
	(new $reportType)->generate($data);
}