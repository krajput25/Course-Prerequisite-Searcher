<html lang="en">

<head>
  <title>CIS 3760 Group 201</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style/navbar.css">
  <link rel="stylesheet" href="style/style.css">
  <link rel="icon" type="image/x-icon" href="/data/images/MtnFav.ico">
</head>

<body>
  <div class="navbar">
    <button class="neumorphic" onclick="location.href='/sub_sites/Tree/'"><span>Tree Model</span></button>
    <button class="neumorphic" onclick="location.href='/sub_sites/Documentation'"><span>API Documentation</span></button>
    <button class="neumorphic" onclick="location.href='/'"><span>Home</span></button>
    <button class="neumorphic" onclick="location.href='/sub_sites/Course_Searcher/index.php'"><span>Course Searcher</span></button>
    <button class="neumorphic" onclick="location.href='/sub_sites/Update_Course/'"><span>Update Course</span></button>
  </div>

  <div class="content">
    <div class="intro">
      <h1>Group 201 Course Searcher</h1>
      <p>Find the courses you can take! Easily plan your course schedule at the University of Guelph.</p>
    </div>

    <div class="floating-images">
      <div class="image-container">
        <video autoplay loop muted class="floating">
          <source src="data/images/search-cap.mp4" type="video/mp4">
          Your browser does not support the video tag.
        </video>
        <p class="image-caption">Updated Sprint 6 Search Interface</p>
      </div>
      <div class="image-container">
        <video autoplay loop muted class="floating">
          <source src="/data/images/search-cap-2.mp4" type="video/mp4">
          Your browser does not support the video tag.
        </video>
        <p class="image-caption">Original Excel Course Searcher</p>
      </div>
      <div class="image-container">
        <video autoplay loop muted class="floating">
          <source src="/data/images/search-cap-3.mp4" type="video/mp4">
          Your browser does not support the video tag.
        </video>
        <p class="image-caption">Our Comprehensive API Docs</p>
      </div>
    </div>

    <div class="features">
      <article>
        <div class="feature">
          <h3>Thank you for using our site!</h3>
          <p>If you would like to read more about the developers, look below</p>
        </div>
      </article>
    </div>

    <div id="scrollIndicator" class="scroll-indicator"></div>

    <div class="profile-cards-container">
      <div class="profile-card">
        <div class="profile-image">
          <img src="/data/images/nw-sm.jpg" alt="Nick Face">
        </div>
        <div class="profile-info">
          <h2><a id="teamMembers">Nick "PHP Hater" Waller</a></h2>
          <p>Avid programmer and nerd, former soccer referee.</p>
        </div>
      </div>

      <div class="profile-card">
        <div class="profile-image">
          <img src="/data/images/profile-pic.jpg" alt="Michelle, Default Profile Picture">
        </div>
        <div class="profile-info">
          <h2>Michelle Pham</h2>
          <p>Huge lover of food, sleep and dogs.</p>
        </div>
      </div>

      <div class="profile-card">
        <div class="profile-image">
          <img src="/data/images/ae-sm.jpg" alt="Ala'a Face">
        </div>
        <div class="profile-info">
          <h2>Ala'a "Ginger Beard" El-Hayek</h2>
          <p>Car Wizard, Tech Enthuisiast, Junior Chef/Master Eater, Cycling, Swimming, yeah...</p>
        </div>
      </div>

      <div class="profile-card">
        <div class="profile-image">
          <img src="/data/images/zc-sm.png" alt="Zach Face">
        </div>
        <div class="profile-info">
          <h2>Zach "Attac" Cymbaluk</h2>
          <p>A part time music producer, astronomer, and tennis champ.</p>
        </div>
      </div>

      <div class="profile-card">
        <div class="profile-image">
          <img src="/data/images/profile-pic.jpg" alt="Khushi, Default Profile Picture">
        </div>
        <div class="profile-info">
          <h2>Khushi Girish Rajput</h2>
          <p>Obsessed with cats, biryani, music, and travel.</p>
        </div>
      </div>

      <div class="profile-card">
        <div class="profile-image">
          <img src="/data/images/profile-pic.jpg" alt="Chinaza, Default Profile Picture">
        </div>
        <div class="profile-info" style="overflow:auto;">
          <h2>Chinaza Anyaegbunam</h2>
          <p>Bodybuilder, fitness enthusiast and dancer.</p>
        </div>
      </div>

    </div>

  </div>
  </div>





  <!-- The overlay container -->
  <div id="overlay" class="overlay">
    <div class="overlay-content">
      <h1>About the Undergraduate Prerequisite Course Generator</h1>
      <ol>
        <li>
          <p>Click the gradient download button on our site to get the XLSM file.</p>
        </li>
        <li>
          <p>Right-click the downloaded file > Properties > Check "Unblock" under attributes.</p>
        </li>
        <li>
          <p>Open the XLSM file and click "Enable Content" on the yellow ribbon at the top.</p>
        </li>
        <li>
          <p>Input courses in their respective columns using format: CIS*3760. (One course per cell).</p>
        </li>
        <li>
          <p>Click "Submit Data". After a short wait, you'll see courses you're eligible for.</p>
        </li>
        <li>
          <p>To view all courses without prerequisites, click the blue "View Courses with No Prerequisites" button.</p>
        </li>
      </ol>
      <br>
      <button id="closeOverlay" class="closeOverlay">Close</button>
    </div>
  </div>

  <script src="/js/index.js"></script>


  <!-- Allows user to click outside of overlay without using close button to close -->
  <script>
    document.getElementById('overlay').addEventListener('click', function(event) {
      // Check if clicked element is overlay itself
      if (event.target == this) {
        this.style.display = 'none'; // If not, hide overlay
      }
    });
  </script>



</body>

</html>