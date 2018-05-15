<?php if (!empty($orders)): ?>
    <?php foreach ($orders['items'] as $order): ?>
<?=$order['tracking_number']?> (<?=$order['status']?>)<?=!empty($order['history']) ? ':' : ''?>
<?php
    if (empty($order['history'])) {
        //Skip the rest of the output since it is probably an api fetch issue
        echo PHP_EOL.PHP_EOL;
        continue;
    }
    
    echo PHP_EOL.'  history:'.PHP_EOL;
    
    for ($ctr = 0; $ctr < count($order['history']); $ctr++) {
        echo '    '.$order['history'][$ctr]['date'];
        echo ': ';
        echo $order['history'][$ctr]['status'];
        echo PHP_EOL;
    }
?>
  breakdown:
    subtotal: <?=$order['subtotal']?>    
    shipping: <?=$order['shipping']?>    
    tax: <?=$order['tax']?>    
    fee: <?=$order['fee']?>    
    insurance: <?=$order['insurance']?>    
    discount: <?=$order['discount']?>    
    total: <?=$order['total']?>
    
  fees:
    shipping fee: <?=$order['shipping_fee']?>    
    insurance_fee: <?=$order['insurance_fee']?>    
    transaction_fee: <?=$order['transaction_fee'].PHP_EOL.PHP_EOL?>
<?php endforeach ?>
total collections: <?=$orders['total_collections'].PHP_EOL?>
total sales: <?=$orders['total_sales']?>
<?php else: ?>
    No orders
<?php endif ?>
