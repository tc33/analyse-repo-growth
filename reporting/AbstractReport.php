<?php

namespace TC33\AnalyseRepoGrowth;

abstract class AbstractReport implements Report {

	public function __construct(
		protected string $reportsDir,
		protected string $dataDir
	) {}

	public abstract function generate(array $data);
}