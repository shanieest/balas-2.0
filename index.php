<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Barangay Balas, Mexico, Pampanga</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css" />

  <style>
    
    .hero {
      /*background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('images/barangay-bg.jpg') center/cover no-repeat;*/
      background: linear-gradient(to right, #0033cc 0%, #990000 97%);
      color: white;
      height: 70vh;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
    }

    .section-title {
      margin-top: 4rem;
      margin-bottom: 2rem;
      text-align: center;
    }

    .info-card {
      transition: transform 0.3s ease;
    }

    .info-card:hover {
      transform: translateY(-5px);
    }

    </style>
</head>
<body>
  <?php include 'includes/header.php'; ?>

  <!-- Hero Section -->
  <section class="hero">
    <div class="container">
      <h1 class="display-4 fw-bolder">Barangay Balas Online Management System</h1>
      <p class="lead">Serving the community of Balas, Mexico, Pampanga</p>
    </div>
  </section>

        <!-- About Us Section -->
    <section id="about" class="py-5">
      <div class="container">
        <h2 class="section-title fw-bolder">About Barangay Balas</h2>
        <div class="row align-items-center">
          <div class="col-md-6">
          <img src="assets\img\balas-logo.png" alt="Barangay Balas Logo" class="img-fluid"/>
          </div>
          <div class="col-md-6">
            <p class="fs-5">
              Barangay Balas is a vibrant and growing community in Mexico, Pampanga. Our mission is to
              provide accessible and transparent local governance to our residents. We aim to offer
              streamlined public services and foster community involvement.
            </p>
            <p class="text-muted">
              Through this online system, we are bringing government services closer to you.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- News and Announcements Section -->
    <!--<section id="news" class="py-5 bg-light">
      <div class="container">
        <h2 class="section-title">Latest News & Announcements</h2>
        <div class="row g-4">
          <div class="col-md-4">
            <div class="card shadow-sm">
              <img src="images/news1.jpg" class="card-img-top" alt="News 1" />
              <div class="card-body">
                <h5 class="card-title">COVID-19 Vaccination Program</h5>
                <p class="card-text">Schedule for the next barangay vaccination drive has been released.</p>
                <a href="#" class="btn btn-outline-warning btn-sm">Read More</a>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card shadow-sm">
              <img src="images/news2.jpg" class="card-img-top" alt="News 2" />
              <div class="card-body">
                <h5 class="card-title">Livelihood Training</h5>
                <p class="card-text">Free training sessions for small businesses and entrepreneurship.</p>
                <a href="#" class="btn btn-outline-warning btn-sm">Read More</a>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card shadow-sm">
              <img src="images/news3.jpg" class="card-img-top" alt="News 3" />
              <div class="card-body">
                <h5 class="card-title">Clean-up Drive</h5>
                <p class="card-text">Join us this Saturday for our barangay-wide community clean-up event.</p>
                <a href="#" class="btn btn-outline-warning btn-sm">Read More</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section> -->

    <!-- Services / Features -->
      <section id="services" class="py-5 bg-light">
        <div class="container">
          <h2 class="section-title fw-bolder">Barangay Online Services</h2>
          <div class="row g-4">
            <div class="col-md-4">
              <div class="card info-card shadow-sm p-3 text-center">
                <div class="card-body">
                  <i class="bi bi-person-badge display-5 text-warning mb-3"></i>
                  <h5 class="card-title">Barangay Clearance</h5>
                  <p class="card-text">Quick and easy processing of barangay clearance documents online.</p>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card info-card shadow-sm p-3 text-center">
                <div class="card-body">
                  <i class="bi bi-house-door display-5 text-warning mb-3"></i>
                  <h5 class="card-title">Residency Certificate</h5>
                  <p class="card-text">Get your certificate of residency without visiting the barangay hall.</p>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card info-card shadow-sm p-3 text-center">
                <div class="card-body">
                  <i class="bi bi-people-fill display-5 text-warning mb-3"></i>
                  <h5 class="card-title">Indigency</h5>
                  <p class="card-text">Accessing medical assistance, and educational support.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Map Section -->
    <section id="map" class="py-5">
        <div class="container">
            <h2 class="section-title fw-bolder">Barangay Balas Location</h2>
            <div class="row">
            <div class="h-100 rounded shadow overflow-hidden">
              <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3857.086160278755!2d120.7449!3d15.0733!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0000000000000000%3A0x0000000000000000!2sBarangay%20Balas%2C%20Mexico%2C%20Pampanga!5e0!3m2!1sen!2sph!4v0000000000"
                width="100%" height="350" style="border:0;" allowfullscreen loading="lazy">
              </iframe>
            </div>
            </div>
        </div>
    </section>

    <!-- Contact Us Section -->
    <section id="contact" class="py-5 bg-light">
        <div class="container">
          <div class="row align-items-center g-5">
            
            <!-- Left: Contact Info -->
            <div class="col-md-6">
              <div class="mb-4">
                <h2 class="fw-bolder ">Get In Touch</h2>
                <p class="text-muted">We're happy to assist you. Reach out anytime, and we'll respond as soon as we can.</p>
              </div>

              <div class="d-flex align-items-start mb-3">
                <div class="me-3 fs-4 text-warning"><i class="bi bi-geo-alt-fill"></i></div>
                <div>
                  <h6 class="mb-1 fw-semibold">Address</h6>
                  <p class="mb-0">Barangay Hall, Balas, Mexico, Pampanga</p>
                </div>
              </div>

              <div class="d-flex align-items-start mb-3">
                <div class="me-3 fs-4 text-warning"><i class="bi bi-telephone-fill"></i></div>
                <div>
                  <h6 class="mb-1 fw-semibold">Phone</h6>
                  <p class="mb-0">+63 123 456 7890</p>
                </div>
              </div>

              <div class="d-flex align-items-start">
                <div class="me-3 fs-4 text-warning"><i class="bi bi-envelope-fill"></i></div>
                <div>
                  <h6 class="mb-1 fw-semibold">Email</h6>
                  <p class="mb-0">balas@gmail.com</p>
                </div>
              </div>
            </div>

            <!-- Right: Contact Form -->
            <div class="col-md-6">
              <div class="card border-0 shadow-sm p-4 rounded-4">
                <form>
                  <div class="mb-3">
                    <label class="form-label">Your Name</label>
                    <input type="text" class="form-control" placeholder="Juan Dela Cruz" />
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Your Email</label>
                    <input type="email" class="form-control" placeholder="juan@example.com" />
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Message</label>
                    <textarea class="form-control" rows="4" placeholder="Write your message here..."></textarea>
                  </div>
                  <button class="btn btn-warning w-100" type="submit">Send Message</button>
                </form>
              </div>
            </div>

          </div>
        </div>
      </section>



  <!-- Footer -->
 <?php include 'includes/footer.php'; ?>
 <?php include 'includes/foot.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
