<?php

namespace TC33\AnalyseRepoGrowth;

use DateTime;

class LinesByDateChart implements Report {

	public function generate(array $data) {
		$versions    = $this->versions($data);
		$totalCounts = array_column(array_column($data, 'SUM'), 'code');
		$totalCounts = array_combine($versions, $totalCounts);
		$dates       = $this->versionDates();

		file_put_contents(Report::REPORTS_DIR . '/lines-by-date-chart.html', $this->chartSource($dates, $totalCounts));
	}

	private function versions(array $data): array {
		return array_keys($data);
	}

	private function versionDates(): array {
		$versionsFile = fopen('../data/versions.csv', 'r');
		$columns      = array_flip(fgetcsv($versionsFile, 0, ','));
		$versionDates = [];

		while (! feof($versionsFile)) {
			$row = fgetcsv($versionsFile, 0, ',');

			if (! $row) {
				continue;
			}

			$version = $row[$columns['version']];
			$date    = $row[$columns['date']];

			$date = DateTime::createFromFormat('Y-m-d H:i:s O', $date);

			$versionDates[$version] = $date->format('Y-m-d H:i:s');
		}
		fclose($versionsFile);

		return $versionDates;
	}

	private function chartSource(array $dates, array $totalCounts) {
		ob_start();
		?>

		<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1"></script>
		<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
		<canvas id="lines-by-date-chart" width="400" height="400"></canvas>
		<script>
			var ctx = document.getElementById('lines-by-date-chart');
			var myChart = new Chart(ctx, {
				type:    'line',
				data:    {
					datasets: [{
						label:           'Lines of code',
						backgroundColor: 'rgba(54, 162, 235, 0.2)',
						borderColor:     'rgba(54, 162, 235, 1)',
						data:            [
							<?php foreach ($totalCounts as $version => $count) : ?>
							{
								x: '<?php echo $dates[$version]; ?>',
								y: <?php echo $count; ?>,
							},
							<?php endforeach; ?>
						]
					}]
				},
				options: {
					scales: {
						x: {
							type: 'time'
						}
					}
				}
			});
		</script>

		<?php
		return ob_get_clean();
	}
}