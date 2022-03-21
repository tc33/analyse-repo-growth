<?php

use TC33\AnalyseRepoGrowth\DataLoader;
use TC33\AnalyseRepoGrowth\LinesByDateChart;
use TC33\AnalyseRepoGrowth\LinesByVersionChart;
use TC33\AnalyseRepoGrowth\LinesByLanguageChart;

include __DIR__ . '/DataLoader.php';
include __DIR__ . '/Report.php';
include __DIR__ . '/AbstractReport.php';
include __DIR__ . '/LinesByVersionReport.php';
include __DIR__ . '/LinesByVersionChart.php';
include __DIR__ . '/LinesByLanguageChart.php';
include __DIR__ . '/LinesByDateChart.php';
include __DIR__ . '/CSVGenerator.php';

// Read arguments
$outputDir = $argv[1] ?? '../output';

$reportsDir = $outputDir . '/reports';
$dataDir    = $outputDir . '/data';
$dataLoader = new DataLoader($dataDir . '/counts/');
$data       = $dataLoader->getData();

$reports = [LinesByVersionReport::class, LinesByVersionChart::class, LinesByLanguageChart::class, LinesByDateChart::class];

if (! file_exists($reportsDir)) {
	mkdir($reportsDir);
}

foreach ($reports as $reportType) {
	(new $reportType($reportsDir, $dataDir))->generate($data);
}