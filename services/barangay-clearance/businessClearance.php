
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Barangay Balas - Business Clearance Certification</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

  <style>
    .hero-section {
     /*background: url('your-hero-image.jpg') center center/cover no-repeat;*/
      background: linear-gradient(to right, #0033cc 0%, #990000 97%);
      color: white;
      padding: 100px 20px;
      text-align: center;
    }
    .form-section {
      padding: 40px 20px;
      background-color: #f8f9fa;
    }
    .services-section {
      padding: 40px 20px;
      background-color: #1a1a1a;
      color: white;
    }
    .service-box {
      background-color: #003366;
      color: black;
      border-radius: 10px;
      padding: 30px 15px;
      text-align: center;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      cursor: pointer;
    }
    .service-box:hover {
      background-color:  #f8f9fa;
      transform: translateY(-10px) scale(1.05);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<body>
<?php include '../../includes/header.php'; ?>

<!-- Hero Section -->
<div class="hero-section">
  <h2 class="fw-bold">CERTIFICATION FOR</h2>
  <h1 class="display-5 fw-bold text-warning">Business Clearance</h1>
</div>

<!-- Form Section -->
<div class="form-section ">
  <form>
    <div class="row g-3">
      <div class="col-md-4">
        <input type="text" class="form-control" placeholder="First Name *">
      </div>
      <div class="col-md-4">
        <input type="text" class="form-control" placeholder="Middle Name *">
      </div>
      <div class="col-md-4">
        <input type="text" class="form-control" placeholder="Last Name *">
      </div>
      <div class="col-md-6">
        <input type="text" class="form-control" placeholder="House No. *">
      </div>
      <div class="col-md-6">
        <input type="text" class="form-control" placeholder="Purok *">
      </div>
      <div class="col-md-4">
        <input type="number" class="form-control" placeholder="Age *">
      </div>
      <div class="col-md-4">
        <input type="text" class="form-control" placeholder="Civil Status *">
      </div>
      <div class="col-md-4">
        <input type="text" class="form-control" placeholder="Purpose *">
      </div>
      <div class="col-md-4">
        <input type="email" class="form-control" placeholder="Email *">
      </div>
      <div class="col-md-8">
        <label for="shipping" class="form-label">Shipping Method *</label>
        <select class="form-select" aria-label="Default select example">
          <option selected>Pick Up (Claim within 24 hrs from 10am–5pm)</option>
          <option>Deliver (charge)</option>
        </select>
      </div>
    </div>
    <div class="text-center mt-4">
      <button type="submit" class="btn btn-primary px-5">Send</button>
    </div>
  </form>
</div>

 <?php include '..\..\includes\foot.php'; ?>
 <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init();
</script>

</body>
</html>
