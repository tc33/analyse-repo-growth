<?php

namespace TC33\AnalyseRepoGrowth;

class LinesByLanguageChart extends AbstractReport {

	const BG_COLOURS      = [
		'rgba(255, 99, 132, 0.2)',
		'rgba(75, 192, 192, 0.2)',
		'rgba(255, 206, 86, 0.2)',
		'rgba(153, 102, 255, 0.2)',
		'rgba(255, 159, 64, 0.2)',
		'rgba(106, 176, 76, 0.2)',
		'rgba(19, 15, 64, 0.2)',
		'rgba(104, 109, 224, 0.2)',
		'rgba(224, 86, 253, 0.2)',
		'rgba(149, 175, 192, 0.2)',
		'rgba(179, 55, 113, 0.2)',
		'rgba(189, 197, 129, 0.2)',
		'rgba(55, 66, 250, 0.2)',
	];
	const BORDER_COLOURS  = [
		'rgba(255, 99, 132, 1)',
		'rgba(75, 192, 192, 1)',
		'rgba(255, 206, 86, 1)',
		'rgba(153, 102, 255, 1)',
		'rgba(255, 159, 64, 1)',
		'rgba(106, 176, 76, 1)',
		'rgba(19, 15, 64, 1)',
		'rgba(104, 109, 224, 1)',
		'rgba(224, 86, 253, 1)',
		'rgba(149, 175, 192, 1)',
		'rgba(179, 55, 113, 1)',
		'rgba(189, 197, 129, 1)',
		'rgba(55, 66, 250, 1)'
	];
	const OTHER_LANGUAGES = ['XML', 'SVG', 'Sass', 'Markdown', 'JSON'];

	public function __construct(string $reportsDir, string $dataDir) {
		parent::__construct($reportsDir, $dataDir);
	}

	public function generate(array $data) {
		$languages = array_diff($this->languages($data), self::OTHER_LANGUAGES, ['SUM']);
		$versions  = $this->versions($data);
		$counts    = [];

		foreach ($languages as $language) {
			foreach ($data as $versionData) {
				$counts[$language][] = $versionData[$language]['code'] ?? 0;
			}
		}

		$counts['Other'] = [];

		foreach ($data as $version => $versionData) {
			$count = 0;

			foreach (self::OTHER_LANGUAGES as $language) {
				$count += $versionData[$language]['code'] ?? 0;
			}

			$counts['Other'][] = $count;
		}

		file_put_contents($this->reportsDir . '/lines-by-language-chart.html', $this->chartSource($versions, $counts));
	}

	private function languages(array $data): array {
		$languages = [];

		foreach ($data as $versionData) {
			$languages = array_unique(array_merge($languages, array_keys($versionData)));
		}

		return $languages;
	}

	private function versions(array $data): array {
		return array_keys($data);
	}

	private function chartSource(array $versions, array $counts) {
		$versionsString = "'" . implode("','", $versions) . "'";
		$languages      = array_keys($counts);

		ob_start();
		?>

		<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1"></script>
		<canvas id="lines-by-language-chart" width="400" height="400"></canvas>
		<script>
			var ctx = document.getElementById('lines-by-language-chart');
			var myChart = new Chart(ctx, {
				type:    'bar',
				data:    {
					labels:   [<?php echo $versionsString; ?>],
					datasets: [
						<?php foreach ($languages as $index => $language) : ?>
						{
							label:           '<?php echo $language; ?>',
							data:            [<?php echo implode(",", $counts[$language]); ?>],
							backgroundColor: '<?php echo self::BG_COLOURS[$index]; ?>',
							borderColor:     '<?php echo self::BORDER_COLOURS[$index]; ?>',
							borderWidth:     1
						},
						<?php endforeach; ?>
					]
				},
				options: {
					scales: {
						x: {
							stacked: true
						},
						y: {
							stacked: true
						}
					}
				}
			});
		</script>

		<?php
		return ob_get_clean();
	}
}