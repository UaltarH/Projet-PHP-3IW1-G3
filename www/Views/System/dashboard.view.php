<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="pagetitle">
    <h1>Dashboard</h1>
</div>
<section class="section dashboard">
    <div class="row">

        
        <div class="col-lg-8">
            <div class="row">

                
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title"><span>Nombre total d'utilisateurs</span></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ps-3">
                                    <h6><?php echo $totalUsers; ?></h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card revenue-card">
                        <div class="card-body">
                            <h5 class="card-title"><span>Nombre total d'articles</span></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-bag"></i>
                                </div>
                                <div class="ps-3">
                                    <h6><?php echo $totalArticles; ?></h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                
                <div class="col-xxl-4 col-xl-12">

                    <div class="card info-card customers-card">
                        <div class="card-body">
                            <h5 class="card-title"><span>Nombre total de jeux</span></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-controller"></i>
                                </div>
                                <div class="ps-3">
                                    <h6><?php echo $totalJeux; ?></h6>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Nombre de jeux par catégorie</h5>
                            <div>
                                <?php
                                $donneesJSON = json_encode($totalGamesByCategories);
                                ?>
                                <div class="canvasContainer">
                                    <canvas id="totalGamesByCategoriesChart"></canvas>
                                </div>
                                <script>
                                    var data = <?php echo $donneesJSON; ?>;

                                    var labels = data.map(function (row) {
                                        return row.category_name;
                                    });

                                    var values = data.map(function (row) {
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
                        </div>
                    </div>
                </div>

                
                <div class="col-12">
                    <div class="card recent-sales overflow-auto">
                        <div class="card-body">
                            <h5 class="card-title">Nombre de nouveaux utilisateurs <span>| Par jour sur le mois dernier</span></h5>
                            <div>
                                <?php
                                $donneesJSON = json_encode($newUsersPerDay);
                                ?>
                                <div class="canvasContainer">
                                    <canvas id="newUsersPerDayChart"></canvas>
                                </div>
                                <script>
                                    var donnees = <?php echo $donneesJSON; ?>;

                                    function filterLabels(labels) {
                                        return labels.map(function (label, index) {
                                            return index % 5 === 0 ? label : '';
                                        });
                                    }

                                    var dates = donnees.map(function (item) {
                                        return item.date;
                                    });
                                    var inscriptions = donnees.map(function (item) {
                                        return item.count;
                                    });
                                    var filteredDates = filterLabels(dates);

                                    var ctx = document.getElementById('newUsersPerDayChart').getContext('2d');
                                    new Chart(ctx, {
                                        type: 'line',
                                        data: {
                                            labels: filteredDates,
                                            datasets: [{
                                                label: "Nombre d'inscriptions",
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
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-lg-4">

            
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Commentaires <span>| En attente de modération</span></h5>

                    <div class="activity">
                        <?php foreach ($unmoderatedComment as $comment): ?>
                            <div class="activity-item d-flex">
                                <div class="activite-label"><?= $comment->getCreationDate() ?></div>
                                <i class='bi bi-circle-fill activity-badge text-warning align-self-start'></i>
                                <div class="activity-content">
                                    <?= $comment->getContent() ?>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>