<?php
require_once 'includes/header.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Initialize variables
$category_filter = '';
$search_filter = '';
$sort_column = 'b.id';
$sort_order = 'ASC';
$items_per_page = 12;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;


$categories_query = "SELECT * FROM categories";
$categories_result = $conn->query($categories_query);
$categories = $categories_result->fetch_all(MYSQLI_ASSOC);

// Apply filters and sorting
if (isset($_GET['category'])) {
    $category_id = (int)$_GET['category'];
    $category_filter = "AND b.category_id = $category_id";
}

if (isset($_GET['search'])) {
    $search_term = $conn->real_escape_string($_GET['search']);
    $search_filter = "AND (b.name LIKE '%$search_term%' OR b.description LIKE '%$search_term%')";
}

if (isset($_GET['sort'])) {
    $sort = $_GET['sort'];
    switch ($sort) {
        case 'price_asc':
            $sort_column = 'b.price';
            $sort_order = 'ASC';
            break;
        case 'price_desc':
            $sort_column = 'b.price';
            $sort_order = 'DESC';
            break;
        default:
            $sort_column = 'b.id';
            $sort_order = 'ASC';
    }
}

// Construct and execute the SQL query
$sql = "SELECT b.*, c.name AS category_name, GROUP_CONCAT(CONCAT('./uploads/', bi.image_url)) AS image_urls 
        FROM bags b 
        LEFT JOIN categories c ON b.category_id = c.id 
        LEFT JOIN bag_images bi ON b.id = bi.bag_id 
        WHERE 1=1 $category_filter $search_filter 
        GROUP BY b.id 
        ORDER BY $sort_column $sort_order 
        LIMIT $offset, $items_per_page";

$result = $conn->query($sql);
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Get total number of bags for pagination
$total_bags_query = "SELECT COUNT(DISTINCT b.id) as total FROM bags b WHERE 1=1 $category_filter $search_filter";
$total_bags_result = $conn->query($total_bags_query);
$total_bags = $total_bags_result->fetch_assoc()['total'];
$total_pages = ceil($total_bags / $items_per_page);

?>
    

    <div class="container">
        <h1 class="page-title">Our Bag Collection</h1>
          <div class="filters">
              <select id="category-filter" class="filter-select">
                  <option value="">All Categories</option>
                  <?php foreach ($categories as $cat): ?>
                      <option value="<?php echo $cat['id']; ?>" <?php echo (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'selected' : ''; ?>>
                          <?php echo htmlspecialchars($cat['name']); ?>
                      </option>
                  <?php endforeach; ?>
              </select>
              <select id="sort-options" class="filter-select">
                  <option value="">Sort By</option>
                  <option value="price_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') ? 'selected' : ''; ?>>Price: Low to High</option>
                  <option value="price_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') ? 'selected' : ''; ?>>Price: High to Low</option>
              </select>
              <div class="search-container">
                  <input type="text" id="search-input" placeholder="Search bags..." class="search-input" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                  <button id="search-button" class="search-button">Search</button>
              </div>
          </div>

          <div class="bag-grid ">
            <?php while ($bag = $result->fetch_assoc()): ?>
                <?php $image_urls = $bag['image_urls'] ? explode(',', $bag['image_urls']) : []; ?>
                <div class="bag-card">
                    <div class="image-carousel">
                        <div class="carousel-container">
                            <?php foreach ($image_urls as $image_url): ?>
                                <img src="<?php echo $image_url; ?>" alt="<?php echo $bag['name']; ?>" class="bag-image">
                            <?php endforeach; ?>
                        </div>
                        <?php if (count($image_urls) > 1): ?>
                            <button class="carousel-nav carousel-prev">❮</button>
                            <button class="carousel-nav carousel-next">❯</button>
                        <?php endif; ?>
                    </div>
                    <div class="bag-info">
                        <h2 class="bag-name"><?php echo $bag['name']; ?></h2>
                        <p class="bag-category"><?php echo $bag['category_name']; ?></p>
                        <div class="bag-footer">
                            <p class="bag-price">Rs <?php echo number_format($bag['price'], 2); ?></p>
                            <a href="bag_details.php?id=<?php echo $bag['id']; ?>" class="view-details">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="page-link <?php echo $page === $i ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
    </div>

 

    <?php include 'includes/footer.php'; ?>

    <style>
    body {
        font-family: 'Arial', sans-serif;
        line-height: 1.6;
        color: #333;
        background-color: #f8f8f8;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    .page-title {
        font-size: 2.5rem;
        font-weight: 300;
        margin-bottom: 2rem;
        text-align: center;
    }

    .filters {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .filter-select, .search-input, .search-button {
        padding: 0.5rem 1rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
    }

    .search-container {
        display: flex;
    }

    .search-button {
        background-color: #007bff;
        color: white;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .search-button:hover {
        background-color: #0056b3;
    }

    .bag-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
    }

    .bag-card {
        background-color: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .bag-card:hover {
        transform: translateY(-5px);
    }
      .image-carousel {
          position: relative;
          height: 250px;
          overflow: hidden;
          background-color: #f0f0f0; /* Light gray background */
      }
    .carousel-container {
        display: flex;
        transition: transform 0.3s ease;
        width: 100%;
    }

    .bag-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        object-position: center;
        flex-shrink: 0;
    }


    .carousel-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(255, 255, 255, 0.7);
        border: none;
        font-size: 1.5rem;
        padding: 0.5rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .carousel-prev {
        left: 10px;
    }

    .carousel-next {
        right: 10px;
    }

    .carousel-nav:hover {
        background-color: rgba(255, 255, 255, 0.9);
    }

    .bag-info {
        padding: 1rem;
    }

    .bag-name {
        font-size: 1.2rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .bag-category {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 1rem;
    }

    .bag-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .bag-price {
        font-size: 1.2rem;
        font-weight: 600;
        color: #007bff;
    }

    .view-details {
        background-color: #007bff;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .view-details:hover {
        background-color: #0056b3;
    }

    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 2rem;
    }

    .page-link {
        padding: 0.5rem 1rem;
        margin: 0 0.25rem;
        background-color: white;
        border: 1px solid #ddd;
        color: #007bff;
        text-decoration: none;
        border-radius: 4px;
        transition: background-color 0.3s ease;
    }

    .page-link:hover, .page-link.active {
        background-color: #007bff;
        color: white;
    }
    </style>
      <script>
      document.addEventListener('DOMContentLoaded', () => {
          const carousels = document.querySelectorAll('.image-carousel');




          carousels.forEach(carousel => {
              const container = carousel.querySelector('.carousel-container');
              const prevBtn = carousel.querySelector('.carousel-prev');
              const nextBtn = carousel.querySelector('.carousel-next');
              const images = container.querySelectorAll('img');
              let currentIndex = 0;







              // Set the width of the container to match the number of images
              container.style.width = `${images.length * 100}%`;



              // Set each image width to be a fraction of the container width
              images.forEach(img => {
                  img.style.width = `${100 / images.length}%`;
              });





              if (images.length <= 1) {
                  if (prevBtn) prevBtn.style.display = 'none';
                  if (nextBtn) nextBtn.style.display = 'none';
                  return;
              }






              function showImage(index) {
                  container.style.transform = `translateX(-${index * (100 / images.length)}%)`;
              }




              if (prevBtn) {
                  prevBtn.addEventListener('click', () => {
                      currentIndex = (currentIndex - 1 + images.length) % images.length;
                      showImage(currentIndex);
                  });
              }







              if (nextBtn) {
                  nextBtn.addEventListener('click', () => {
                      currentIndex = (currentIndex + 1) % images.length;
                      showImage(currentIndex);
                  });
              }
          });








          const categoryFilter = document.getElementById('category-filter');
          const sortOptions = document.getElementById('sort-options');
          const searchInput = document.getElementById('search-input');
          const searchButton = document.getElementById('search-button');



            function updateURL() {
                const params = new URLSearchParams(window.location.search);
            
                if (categoryFilter.value) {
                    params.set('category', categoryFilter.value);
                } else {
                    params.delete('category');
                }

                if (sortOptions.value) {
                    params.set('sort', sortOptions.value);
                } else {
                    params.delete('sort');
                }
                // ... (rest of the function)

                window.location.href = `${window.location.pathname}?${params.toString()}`;
          }

          categoryFilter.addEventListener('change', updateURL);
          sortOptions.addEventListener('change', updateURL);
          searchButton.addEventListener('click', updateURL);
          searchInput.addEventListener('keypress', (e) => {
              if (e.key === 'Enter') {
                  updateURL();
              }
          });
      });
      </script>
