<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/dbb.php';
require_once __DIR__ . '/includes/functions.php';

// Get database connection
$conn = get_db_connection();

// Fetch categories
$categories = get_categories($conn);

// Fetch featured bags
$featured_bags = get_featured_bags($conn, 3);

?>

<section class="parallax-hero h-screen relative overflow-hidden">
    <div class="parallax-bg absolute inset-0" style="background-image: url('./uploads/4.jpeg');"></div>
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="relative z-10 flex items-center justify-center h-full text-white text-center">
      <div class="space-y-6">
        <h1 class="text-5xl md:text-7xl font-bold animate-fade-in">Welcome to EbagStores</h1>
        <p class="text-xl md:text-3xl animate-fade-in-delay">Elevate Your Style with Our Exquisite Collection</p>
        <a href="./bags.php" class="inline-block bg-gradient-to-r from-purple-500 to-pink-500 text-white px-8 py-4 rounded-full text-lg hover:from-pink-500 hover:to-purple-500 transition duration-300 animate-bounce">Explore Now</a>
      </div>
    </div>
</section>

<script>// Carousel functionality
let currentSlide = 0;
const slides = document.querySelectorAll('.carousel-item');

function showSlide(index) {
      slides.forEach((slide, i) => {
          slide.style.opacity = i === index ? '1' : '0';
      });
}

function nextSlide() {
      currentSlide = (currentSlide + 1) % slides.length;
      showSlide(currentSlide);
}

setInterval(nextSlide, 3000);
showSlide(0);
</script>
<!-- All Categories Section -->
<div id="categories" class="bg-gray-100 py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold mb-8">All Categories</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php
            foreach ($categories as $category) {
                echo "<a href='bags.php?category={$category['id']}' class='bg-white p-4 rounded-lg shadow-md text-center hover:bg-blue-500 hover:text-white transition duration-300'>";
                echo $category['name'];
                echo "</a>";
            }
            ?>
        </div>
    </div>
</div>

<!-- Featured Bags Section -->
<?php if (!empty($featured_bags)): ?>
<div class="container mx-auto px-4 my-16">
    <h2 class="text-3xl font-bold mb-8">Featured Bags</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <?php 
        $count = 0;
        foreach ($featured_bags as $bag): 
            if ($count >= 3) break;
        ?>
            <div class="bag-card">
                <div class="image-carousel">
                    <div class="carousel-container">
                        <?php foreach ($bag['image_urls'] as $image_url): ?>
                            <img src="<?php echo $image_url; ?>" alt="<?php echo htmlspecialchars($bag['name']); ?>" class="bag-image">
                        <?php endforeach; ?>
                    </div>
                    <?php if (count($bag['image_urls']) > 1): ?>
                        <button class="carousel-nav carousel-prev">❮</button>
                        <button class="carousel-nav carousel-next">❯</button>
                    <?php endif; ?>
                </div>
                <div class="bag-info">
                    <h2 class="bag-name"><?php echo htmlspecialchars($bag['name']); ?></h2>
                    <p class="bag-category"><?php echo htmlspecialchars($bag['category_name']); ?></p>
                    <div class="bag-footer">
                        <p class="bag-price">Rs <?php echo number_format($bag['price'], 2); ?></p>
                        <a href="bag_details.php?id=<?php echo $bag['id']; ?>" class="view-details">View Details</a>
                    </div>
                </div>
            </div>
        <?php 
            $count++;
        endforeach; 
        ?>
    </div>
</div>
<?php endif; ?>
<!-- Dynamic Random Categories Section -->
<div class="container mx-auto px-4 my-16">
    <h2 class="text-3xl font-bold mb-8">Featured Categories</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php
        $random_categories = array_rand($categories, min(3, count($categories)));
        foreach ($random_categories as $category_id) {
            $category = $categories[$category_id];
            echo "<div class='bg-white p-6 rounded-lg shadow-md'>";
            echo "<h3 class='text-xl font-semibold mb-4'>{$category['name']}</h3>";
            echo "<p class='mb-4'>Explore our collection of {$category['name']}</p>";
            echo "<a href='bags.php?category={$category['id']}' class='text-blue-500 hover:underline'>Shop {$category['name']}</a>";
            echo "</div>";
        }
        ?>
    </div>
</div>


  <style>
  :root {
      --primary-color: #8A2BE2;
      --secondary-color: #FF69B4;
      --text-color: #333333;
      --background-color: #F8F8F8;
  }

  body {
      font-family: 'Poppins', sans-serif;
      line-height: 1.6;
      color: var(--text-color);
      background-color: var(--background-color);
  }

  h1, h2, h3 {
      font-family: 'Playfair Display', serif;
  }

  .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 2rem;
  }

  .bag-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
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
      background-color: #f0f0f0;
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
      color: var(--primary-color);
  }

  .view-details {
      background-color: var(--primary-color);
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 4px;
      text-decoration: none;
      transition: all 0.3s ease;
  }

  .view-details:hover {
      background-color: var(--secondary-color);
      transform: translateY(-2px);
  }

  .btn-primary {
      background-color: var(--primary-color);
      color: white;
      transition: all 0.3s ease;
  }

  .btn-primary:hover {
      background-color: var(--secondary-color);
      transform: translateY(-2px);
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

        container.style.width = `${images.length * 100}%`;

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
});
</script>

<?php
require_once __DIR__ . '/includes/footer.php';
?>

<script>
  // Smooth scrolling
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      document.querySelector(this.getAttribute('href')).scrollIntoView({
        behavior: 'smooth'
      });
    });
  });

  // Animate elements on scroll
  const animateOnScroll = () => {
    const elements = document.querySelectorAll('.animate-on-scroll');
    elements.forEach(el => {
      const rect = el.getBoundingClientRect();
      const windowHeight = window.innerHeight || document.documentElement.clientHeight;
      if (rect.top <= windowHeight * 0.75) {
        el.classList.add('animated');
      }
    });
  };

  window.addEventListener('scroll', animateOnScroll);
  animateOnScroll(); // Initial check
</script>
