<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<h1>Tableau de bord</h1>

<div id="dashboardSummary">
    <div>
        <h2>Nombre total d'utilisateurs</h2>
        <p><?php echo $totalUsers; ?></p>
    </div>
    <div>
        <h2>Nombre total d'articles</h2>
        <p><?php echo $totalArticles; ?></p>
    </div>
    <div>
        <h2>Nombre total de jeux</h2>
        <p><?php echo $totalJeux; ?></p>
    </div>
</div>

<div>
    <h2>Nombre de nouveaux utilisateurs par jour sur le mois dernier</h2>
    <?php
    $donneesJSON = json_encode($newUsersPerDay);
    ?>
    <div class="canvasContainer"><canvas id="newUsersPerDayChart"></canvas></div>
    <script>
        var donnees = <?php echo $donneesJSON; ?>;

        function filterLabels(labels) {
            return labels.map(function(label, index) {
                return index % 5 === 0 ? label : '';
            });
        }
        var dates = donnees.map(function(item) {
            return item.date;
        });
        var inscriptions = donnees.map(function(item) {
            return item.count;
        });
        var filteredDates = filterLabels(dates);

        var ctx = document.getElementById('newUsersPerDayChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: filteredDates,
                datasets: [{
                    label: "Nombre d'inscriptions par jour",
                    data: inscriptions,
                    backgroundColor: 'rgba(75, 192, 192, 0.8)',
                    borderColor: 'rgba(75, 192, 192, 0.8)',
                    pointRadius: 0,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</div>
<div>
    <h2>Nombre de jeux par catégorie</h2>
    <?php
    $donneesJSON = json_encode($totalGamesByCategories);
    ?>
    <div class="canvasContainer"><canvas id="totalGamesByCategoriesChart"></canvas></div>
    <script>
        var data = <?php echo $donneesJSON; ?>;

        var labels = data.map(function(row) {
            return row.category_name;
        });

        var values = data.map(function(row) {
            return row.jeux_count;
        });

        var ctx = document.getElementById('totalGamesByCategoriesChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Nombre de jeux',
                    data: values,
                    backgroundColor: 'rgba(75, 192, 192, 0.8)',
                    pointRadius: 0,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }
            }
        });
    </script>
</div>
