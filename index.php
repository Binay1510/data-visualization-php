<?php
include "config.php";

$query = 'select * from data';
$result = $mysqli->query($query);

$relevance = "SELECT relevance, likelihood, end_year from data GROUP BY end_year";
$relevanceRecords = $mysqli->query($relevance);

$intensity = "SELECT intensity, end_year FROM data GROUP BY end_year";
$intensityRecords = $mysqli->query($intensity);

$year = "SELECT COUNT(end_year) as ey, end_year from data GROUP BY end_year";
$yearRecords = $mysqli->query($year);

$likelihood_intensity = "SELECT likelihood, intensity, end_year FROM data";
$likelihoodIntensityRecords = $mysqli->query($likelihood_intensity);

$sql = "SELECT country, COUNT(*) AS count FROM data GROUP BY country";
$countryresult = $mysqli->query($sql);
$data = array();
$data[] = ['Country', 'Count'];
while ($row = $countryresult->fetch_assoc()) {
    $data[] = [$row['country'], (int) $row['count']];
}

$topic = "SELECT topic, COUNT(*) AS count FROM data GROUP BY topic";
$topicRecords = $mysqli->query($topic);
$topicdata = array();
$topicdata[] = ['Topic', 'Count'];
while ($row = $topicRecords->fetch_assoc()) {
    $topicdata[] = [$row['topic'], (int) $row['count']];
}

$regions = "SELECT DISTINCT region FROM data";
$regionRecords = $mysqli->query($regions);

$cities = "SELECT DISTINCT city FROM data";
$cityRecords = $mysqli->query($cities);

$sectors = "SELECT DISTINCT sector FROM data";
$sectorRecords = $mysqli->query($sectors);

$pestles = "SELECT DISTINCT pestle FROM data";
$pestRecords = $mysqli->query($pestles);

$sources = "SELECT DISTINCT source FROM data";
$sourceRecords = $mysqli->query($sources);

$swots = "SELECT DISTINCT swot FROM data";
$swotRecords = $mysqli->query($swots);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" href="style.css">
    <style>
        .chart-container {
            margin: 10px 0;
        }

        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }

        .filters div {
            flex: 1 1 calc(33.333% - 40px);
            display: flex;
            flex-direction: column;
        }

        .filters label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .filters select {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ced4da;
        }

        .table thead th {
            background-color: #343a40;
            color: white;
        }

        .table tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
        }

        .table tbody tr:hover {
            background-color: #e9ecef;
        }

        .table tfoot th {
            background-color: #343a40;
            color: white;
        }

        nav {
            background-color: #343a40;
            padding: 15px;
        }

        nav .logo-name {
            display: flex;
            align-items: center;
        }

        nav .logo-image img {
            width: 50px;
            margin-right: 10px;
        }

        nav .logo_name {
            color: white;
            font-size: 24px;
            font-weight: bold;
        }

        .activity .title {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .activity .title i {
            font-size: 24px;
            margin-right: 10px;
        }

        .activity .title span {
            font-size: 20px;
            font-weight: bold;
        }
    </style>
    <title>Admin Dashboard Panel</title>
</head>

<body>
    <nav>
        <div class="container">
            <div class="logo-name">
                <div class="logo-image">
                    <img src="images/logo.png" alt="">
                </div>
                <span class="logo_name">Admin Dashboard</span>
            </div>
        </div>
    </nav>

    <section class="dashboard m-3">
        <div class="dash-content">
            <div class="overview">
                <div class="row">
                    <div class="col-md-6 chart-container">
                        <div id="curve_chart" style="width: 100%; height: 300px;"></div>
                    </div>
                    <div class="col-md-6 chart-container">
                        <div id="likelihood" style="width: 100%; height: 300px;"></div>
                    </div>
                    <div class="col-md-6 chart-container">
                        <div id="barchart_material" style="width: 100%; height: 300px;"></div>
                    </div>
                    <div class="col-md-6 chart-container">
                        <div id="regions_div" style="width: 100%; height: 300px;"></div>
                    </div>
                    <div class="col-md-6 chart-container">
                        <div id="topic_barchart" style="width: 100%; height: 300px;"></div>
                    </div>
                    <div class="col-md-6 chart-container">
                        <div id="likelihood_intensity_chart" style="width: 100%; height: 300px;"></div>
                    </div>
                </div>
            </div>

            <div class="overview">
                <div class="filters">
                    <div>
                        <label for="end_year">End Year:</label>
                        <select id="end_year">
                            <option value="">All</option>
                            <?php
                            $yearOptions = $mysqli->query("SELECT DISTINCT end_year FROM data ORDER BY end_year");
                            while ($row = $yearOptions->fetch_assoc()) {
                                echo "<option value='" . $row['end_year'] . "'>" . $row['end_year'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div>
                        <label for="topic">Topic:</label>
                        <select id="topic">
                            <option value="">All</option>
                            <?php
                            $topicOptions = $mysqli->query("SELECT DISTINCT topic FROM data ORDER BY topic");
                            while ($row = $topicOptions->fetch_assoc()) {
                                echo "<option value='" . $row['topic'] . "'>" . $row['topic'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div>
                        <label for="sector">Sector:</label>
                        <select id="sector">
                            <option value="">All</option>
                            <?php
                            $sectorOptions = $mysqli->query("SELECT DISTINCT sector FROM data ORDER BY sector");
                            while ($row = $sectorOptions->fetch_assoc()) {
                                echo "<option value='" . $row['sector'] . "'>" . $row['sector'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div>
                        <label for="region">Region:</label>
                        <select id="region">
                            <option value="">All</option>
                            <?php
                            $regionOptions = $mysqli->query("SELECT DISTINCT region FROM data ORDER BY region");
                            while ($row = $regionOptions->fetch_assoc()) {
                                echo "<option value='" . $row['region'] . "'>" . $row['region'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div>
                        <label for="pest">PEST:</label>
                        <select id="pest">
                            <option value="">All</option>
                            <?php
                            $pestOptions = $mysqli->query("SELECT DISTINCT pestle FROM data ORDER BY pestle");
                            while ($row = $pestOptions->fetch_assoc()) {
                                echo "<option value='" . $row['pestle'] . "'>" . $row['pestle'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div>
                        <label for="source">Source:</label>
                        <select id="source">
                            <option value="">All</option>
                            <?php
                            $sourceOptions = $mysqli->query("SELECT DISTINCT source FROM data ORDER BY source");
                            while ($row = $sourceOptions->fetch_assoc()) {
                                echo "<option value='" . $row['source'] . "'>" . $row['source'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div>
                        <label for="swot">SWOT:</label>
                        <select id="swot">
                            <option value="">All</option>
                            <?php
                            $swotOptions = $mysqli->query("SELECT DISTINCT swot FROM data ORDER BY swot");
                            while ($row = $swotOptions->fetch_assoc()) {
                                echo "<option value='" . $row['swot'] . "'>" . $row['swot'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div>
                        <label for="country">Country:</label>
                        <select id="country">
                            <option value="">All</option>
                            <?php
                            $countryOptions = $mysqli->query("SELECT DISTINCT country FROM data ORDER BY country");
                            while ($row = $countryOptions->fetch_assoc()) {
                                echo "<option value='" . $row['country'] . "'>" . $row['country'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div>
                        <label for="city">City:</label>
                        <select id="city">
                            <option value="">All</option>
                            <?php
                            $cityOptions = $mysqli->query("SELECT DISTINCT city FROM data ORDER BY city");
                            while ($row = $cityOptions->fetch_assoc()) {
                                echo "<option value='" . $row['city'] . "'>" . $row['city'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>End Year</th>
                                <th>Topics</th>
                                <th>Sector</th>
                                <th>Region</th>
                                <th>PEST</th>
                                <th>Source</th>
                                <th>SWOT</th>
                                <th>Country</th>
                                <th>City</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($result as $row) { ?>
                                <tr>
                                    <td><?php echo $row['end_year']; ?></td>
                                    <td><?php echo $row['topic']; ?></td>
                                    <td><?php echo $row['sector']; ?></td>
                                    <td><?php echo $row['region']; ?></td>
                                    <td><?php echo $row['pestle']; ?></td>
                                    <td><?php echo $row['source']; ?></td>
                                    <td><?php echo $row['swot']; ?></td>
                                    <td><?php echo $row['country']; ?></td>
                                    <td><?php echo $row['city']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>End Year</th>
                                <th>Topics</th>
                                <th>Sector</th>
                                <th>Region</th>
                                <th>PEST</th>
                                <th>Source</th>
                                <th>SWOT</th>
                                <th>Country</th>
                                <th>City</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="script.js"></script>
    <script>
        google.charts.load('current', { 'packages': ['corechart', 'bar', 'geochart'] });
        google.charts.setOnLoadCallback(drawCharts);

        function drawCharts() {
            drawRelevanceChart();
            drawLikelihoodChart();
            drawIntensityChart();
            drawRegionChart();
            drawTopicChart();
        }

        function drawRelevanceChart() {
            var data = google.visualization.arrayToDataTable([
                ['End Year', 'Relevance', 'Likelihood'],
                <?php
                while ($row = $relevanceRecords->fetch_assoc()) {
                    echo "['" . $row['end_year'] . "', " . $row['relevance'] . ", " . $row['likelihood'] . "],";
                }
                ?>
            ]);

            var options = {
                title: 'Relevance and Likelihood Over Years',
                curveType: 'function',
                legend: { position: 'bottom' },
                colors: ['#FF5733', '#C70039']
            };

            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
            chart.draw(data, options);
        }

        function drawLikelihoodChart() {
            var data = google.visualization.arrayToDataTable([
                ['Year', 'Likelihood'],
                <?php
                while ($row = $yearRecords->fetch_assoc()) {
                    echo "['" . $row['end_year'] . "', " . $row['ey'] . "],";
                }
                ?>
            ]);

            var options = {
                title: 'Number of Records Per Year',
                hAxis: { title: 'Year' },
                vAxis: { title: 'Records' },
                legend: { position: 'none' },
                bar: { groupWidth: '75%' },
                colors: ['#1E8449']
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('likelihood'));
            chart.draw(data, options);
        }

        function drawIntensityChart() {
            var data = google.visualization.arrayToDataTable([
                ['End Year', 'Intensity'],
                <?php
                while ($row = $intensityRecords->fetch_assoc()) {
                    echo "['" . $row['end_year'] . "', " . $row['intensity'] . "],";
                }
                ?>
            ]);

            var options = {
                chart: {
                    title: 'Intensity Over Years',
                },
                colors: ['#3498DB']
            };

            var chart = new google.charts.Bar(document.getElementById('barchart_material'));
            chart.draw(data, google.charts.Bar.convertOptions(options));
        }

        function drawRegionChart() {
            var data = google.visualization.arrayToDataTable(<?php echo json_encode($data); ?>);

            var options = {
                title: 'Records by Country',
                colorAxis: { colors: ['#FFDD44', '#FF6F61'] }
            };

            var chart = new google.visualization.GeoChart(document.getElementById('regions_div'));
            chart.draw(data, options);
        }

        function drawTopicChart() {
            var data = google.visualization.arrayToDataTable(<?php echo json_encode($topicdata); ?>);

            var options = {
                chart: {
                    title: 'Records by Topic',
                },
                colors: ['#8E44AD']
            };

            var chart = new google.charts.Bar(document.getElementById('topic_barchart'));
            chart.draw(data, google.charts.Bar.convertOptions(options));
        }
        function drawLikelihoodIntensityChart() {
            var data = google.visualization.arrayToDataTable([
                ['End Year', 'Likelihood', 'Intensity'],
                <?php
                while ($row = $likelihoodIntensityRecords->fetch_assoc()) {
                    echo "['" . $row['end_year'] . "', " . $row['likelihood'] . ", " . $row['intensity'] . "],";
                }
                ?>
            ]);

            var options = {
                title: 'Likelihood and Intensity Over Years',
                hAxis: { title: 'Year' },
                vAxis: { title: 'Values' },
                seriesType: 'bars',
                series: { 1: { type: 'line' } }
            };

            var chart = new google.visualization.ComboChart(document.getElementById('likelihood_intensity_chart'));
            chart.draw(data, options);
        }

        $(document).ready(function () {
            var table = $('#example').DataTable();

            $('#end_year, #topic, #sector, #region, #pest, #source, #swot, #country, #city').on('change', function () {
                var endYear = $('#end_year').val();
                var topic = $('#topic').val();
                var sector = $('#sector').val();
                var region = $('#region').val();
                var pest = $('#pest').val();
                var source = $('#source').val();
                var swot = $('#swot').val();
                var country = $('#country').val();
                var city = $('#city').val();

                table.column(0).search(endYear).column(1).search(topic).column(2).search(sector)
                    .column(3).search(region).column(4).search(pest).column(5).search(source)
                    .column(6).search(swot).column(7).search(country).column(8).search(city).draw();
            });
        });
    </script>
</body>

</html>
