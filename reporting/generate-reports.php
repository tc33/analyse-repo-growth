<?php

use TC33\AnalyseRepoGrowth\DataLoader;
use TC33\AnalyseRepoGrowth\TotalLinesChart;

include __DIR__ . '/DataLoader.php';
include __DIR__ . '/Report.php';
include __DIR__ . '/LinesByVersionReport.php';
include __DIR__ . '/TotalLinesChart.php';
include __DIR__ . '/CSVGenerator.php';

$dataLoader = new DataLoader(__DIR__ . '/../data');
$data       = $dataLoader->getData();

$reports = [LinesByVersionReport::class, TotalLinesChart::class];

foreach ($reports as $reportType) {
	(new $reportType)->generate($data);
}