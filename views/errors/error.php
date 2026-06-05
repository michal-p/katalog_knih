<?php
$code = $code ?? 404;
$title = $title ?? 'Stránka sa nenašla';
$message = $message ?? 'Ľutujeme, ale stránka, ktorú hľadáte, neexistuje.';
?>
<div class="error-page">
    <div class="error-code"><?php echo $code; ?></div>
    <h2><?php echo htmlspecialchars($title); ?></h2>
    <p><?php echo htmlspecialchars($message); ?></p>
    <a href="/" class="btn btn-primary">
        <span>←</span> Späť na úvodnú stránku
    </a>
</div>
