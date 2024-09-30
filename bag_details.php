<?php
require_once 'includes/header.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Get the bag ID from the URL
$bag_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch bag details
$sql = "SELECT b.*, c.name AS category_name, GROUP_CONCAT(CONCAT('./uploads/', bi.image_url)) AS image_urls 
        FROM bags b 
        LEFT JOIN categories c ON b.category_id = c.id 
        LEFT JOIN bag_images bi ON b.id = bi.bag_id 
        WHERE b.id = $bag_id 
        GROUP BY b.id";

$result = $conn->query($sql);

if ($result->num_rows === 0) {
    die("Bag not found");
}

$bag = $result->fetch_assoc();
$image_urls = $bag['image_urls'] ? explode(',', $bag['image_urls']) : [];
?>

<div class="container">
    <div class="bag-details">
        <div class="image-gallery">
            <div class="main-image">
                <img src="<?php echo $image_urls[0]; ?>" alt="<?php echo $bag['name']; ?>" id="main-image">
            </div>
            <div class="thumbnail-container">
                <?php foreach ($image_urls as $index => $image_url): ?>
                    <img src="<?php echo $image_url; ?>" alt="<?php echo $bag['name']; ?>" class="thumbnail" onclick="changeMainImage(this.src)">
                <?php endforeach; ?>
            </div>
        </div>
        <div class="bag-info">
            <h1 class="bag-name"><?php echo $bag['name']; ?></h1>
            <p class="bag-category"><?php echo $bag['category_name']; ?></p>
            <p class="bag-price">Rs <?php echo number_format($bag['price'], 2); ?></p>
            <div class="bag-description">
                <h2>Description</h2>
                <p><?php echo nl2br($bag['description']); ?></p>
            </div>
            
            
            <form id="add-to-cart-form" method="POST" action="add_to_cart.php">
                <input type="hidden" name="bag_id" value="<?php echo $bag_id; ?>">
                <input type="hidden" name="shipping_cost" id="shipping-cost" value="100">
                <input type="number" name="quantity" value="1" min="1" max="10">
                <button type="submit" class="add-to-cart">Add to Cart</button>
            </form>
        </div>
    </div>
</div>

<style>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.bag-details {
    display: flex;
    gap: 2rem;
}

.image-gallery {
    flex: 1;
}

.main-image {
    margin-bottom: 1rem;
}

.main-image img {
    width: 100%;
    height: auto;
    border-radius: 8px;
}
  .thumbnail-container {
      display: flex;
      overflow-x: auto;
      gap: 10px;
      padding: 10px 0;
      max-width: 100%;
  }

  .thumbnail {
      flex: 0 0 auto;
      width: 80px;
      height: 80px;
      object-fit: cover;
      cursor: pointer;
      border: 2px solid transparent;
  }

  .thumbnail.active {
      border-color: #007bff;
  }

.bag-info {
    flex: 1;
}

.bag-name {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.bag-category {
    font-size: 1rem;
    color: #666;
    margin-bottom: 1rem;
}

.bag-price {
    font-size: 1.5rem;
    font-weight: bold;
    color: #007bff;
    margin-bottom: 1rem;
}

.bag-description, .bag-specs {
    margin-bottom: 1.5rem;
}

.bag-description h2, .bag-specs h2 {
    font-size: 1.2rem;
    margin-bottom: 0.5rem;
}

.bag-specs ul {
    list-style-type: none;
    padding: 0;
}

.bag-specs li {
    margin-bottom: 0.5rem;
}

.add-to-cart {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.add-to-cart:hover {
    background-color: #0056b3;
}

@media (max-width: 768px) {
    .bag-details {
        flex-direction: column;
    }
}
</style>


<script>
function changeMainImage(src) {
    document.getElementById('main-image').src = src;
}

document.getElementById('shipping-option').addEventListener('change', function() {
    document.getElementById('shipping-cost').value = this.value;
});
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#add-to-cart-form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'add_to_cart.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                alert('Item added to cart');
                updateCartCount();
            }
        });
    });

    function updateCartCount() {
        $.get('get_cart_count.php', function(count) {
            $('#cart-count').text(count);
        });
    }
});
</script>
  <?php include 'includes/footer.php'; ?>
