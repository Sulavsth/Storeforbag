<!-- Inside the orders table -->
<th>Payment Method</th>
<th>Payment Evidence</th>

<!-- Inside the foreach loop -->
<td><?php echo ucfirst($order['payment_method']); ?></td>
<td>
    <?php if ($order['payment_method'] == 'online' && $order['payment_evidence']): ?>
        <a href="<?php echo $order['payment_evidence']; ?>" target="_blank">View</a>
    <?php else: ?>
        N/A
    <?php endif; ?>
</td>
