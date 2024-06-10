<?php

function renderChartWidget($widget_data) {
    $data = json_decode($widget_data, true);
    $labels = json_encode($data['labels']);
    $values = json_encode($data['values']);
    return "
        <canvas id='chart'></canvas>
        <script>
            var ctx = document.getElementById('chart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: $labels,
                    datasets: [{
                        label: 'Dataset',
                        data: $values,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    ";
}
