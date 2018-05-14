<?php if (!empty($orders)): ?>
    <?php foreach ($orders as $order): ?>
        name:
        history:
            --loop
        breakdown:
        fees:

    <?php endforeach ?>
<?php else: ?>
    No orders
<?php endif; ?>
