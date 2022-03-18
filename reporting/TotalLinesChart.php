<?php

namespace TC33\AnalyseRepoGrowth;

class TotalLinesChart implements Report {

	public function generate(array $data) {
		$versions    = $this->versions($data);
		$totalCounts = array_column( array_column($data, 'SUM'), 'code' );

		file_put_contents(Report::REPORTS_DIR . '/total-lines-chart.html', $this->chartSource($versions, $totalCounts));
	}

	private function versions(array $data): array {
		return array_keys($data);
	}

	private function chartSource(array $versions, array $totalCounts) {
		$versionsString    = "'" . implode("','", $versions) . "'";
		$totalCountsString = implode(",", $totalCounts);

		ob_start();
		?>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1"></script>
<canvas id="total-lines-chart" width="400" height="400"></canvas>
<script>
	var ctx = document.getElementById('total-lines-chart');
	var myChart = new Chart(ctx, {
		type:    'bar',
		data:    {
			labels:   [<?php echo $versionsString; ?>],
			datasets: [{
				label:           'Lines of Code',
				data:            [<?php echo $totalCountsString; ?>],
				backgroundColor: 'rgba(54, 162, 235, 0.2)',
				borderColor:     'rgba(54, 162, 235, 1)',
				borderWidth:     1
			}]
		},
		options: {
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true
					}
				}]
			}
		}
	});
</script>

		<?php
		return ob_get_clean();
	}
}