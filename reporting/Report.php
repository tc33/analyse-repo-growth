<?php

namespace TC33\AnalyseRepoGrowth;

interface Report {

	public function generate(array $data);
}