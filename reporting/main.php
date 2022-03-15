<?php

use TC33\AnalyseRepoGrowth\DataLoader;

include __DIR__ . '/DataLoader.php';

$dataLoader = new DataLoader(__DIR__ . '/../data');
$data       = $dataLoader->getData();
