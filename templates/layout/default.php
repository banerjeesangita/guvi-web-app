<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($this->fetch('title') ?: 'GUVI') ?></title>
    <link rel="icon" type="image/svg+xml" href="/img/favicon.svg">

    <!-- Bootstrap (layout / responsiveness only) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <!-- Exactly two fonts: Inter (body) + Poppins (headings) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">

    <!-- App CSS -->
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <?= $this->fetch('content') ?>

    <!-- jQuery (DOM manipulation + AJAX) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap JS bundle (for components only, e.g. alerts dismiss) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <?= $this->fetch('script') ?>
</body>
</html>
