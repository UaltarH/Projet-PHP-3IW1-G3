<div class="d-flex align-items-center justify-content-center vh-100">
    <div class="text-center">
        <h1 class="display-1 fw-bold"><?= http_response_code() ?></h1>
        <p class="fs-3"> <span class="text-danger">Oops!</span> Bad Request.</p>
        <p class="lead">
        <p><?= $message ?></p>
        </p>
        <a href="/" class="btn btn-primary">Go Home</a>
    </div>
</div>