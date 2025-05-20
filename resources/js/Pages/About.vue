<script setup>
import Footer from '@/Components/Footer.vue';
import Header from '@/Components/Header.vue';
</script>

<template>
  <div class="about">
    <Header activePage="about" />
    <section class="hero bg-about relative bg-cover bg-center h-screen">
      <div class="absolute inset-0 bg-gradient-to-r from-black via-transparent to-black z-0"></div>
      <div class="container mx-auto px-4 md:px-20 flex items-center justify-start h-full z-10">
        <div class="text-white max-w-lg">
          <h2 class="text-4xl font-extrabold">Tentang Kami</h2>
          <p class="leading-relaxed text-lg">
            Kami adalah penyedia ayam potong dan daging segar premium yang berkomitmen
            untuk memberikan produk berkualitas tinggi langsung ke meja makan Anda.
            Dengan proses pengolahan yang higienis dan layanan terbaik, kami
            memastikan kepuasan pelanggan menjadi prioritas utama kami.
          </p>
        </div>
      </div>
    </section>

    <!-- Section: Misi Kami -->
    <section class="bg-white py-16 px-4">
      <div class="container mx-auto text-center">
        <h2 class="text-4xl font-extrabold text-gray-800 mb-10">Misi Kami</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <!-- Misi 1 -->
          <div
            class="p-6 bg-green-50 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-2 transition duration-300">
            <img src="../assets/sparkles.png" alt="Kualitas Terbaik" class="w-16 h-16 mx-auto mb-4" />
            <h3 class="text-2xl font-semibold text-gray-800">Kualitas Terbaik</h3>
            <p class="text-gray-600 mt-2">
              Kami memastikan produk yang kami sediakan selalu segar, higienis,
              dan berkualitas premium.
            </p>
          </div>
          <!-- Misi 2 -->
          <div
            class="p-6 bg-green-50 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-2 transition duration-300">
            <img src="../assets/ranking-star.png" alt="Layanan Unggul" class="w-16 h-16 mx-auto mb-4" />
            <h3 class="text-2xl font-semibold text-gray-800">Layanan Unggul</h3>
            <p class="text-gray-600 mt-2">
              Memberikan pengalaman berbelanja yang mudah, cepat, dan nyaman
              untuk semua pelanggan kami.
            </p>
          </div>
          <!-- Misi 3 -->
          <div
            class="p-6 bg-green-50 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-2 transition duration-300">
            <img src="../assets/tree.png" alt="Sustainability" class="w-16 h-16 mx-auto mb-4" />
            <h3 class="text-2xl font-semibold text-gray-800">Peduli Lingkungan</h3>
            <p class="text-gray-600 mt-2">
              Mendukung keberlanjutan lingkungan dengan proses pengolahan yang
              ramah lingkungan.
            </p>
          </div>
        </div>
      </div>
    </section>

    <section class="about-section">
    <div class="about-container">
      <div class="about-text">
        <h1 class="about-title text-green-500">Ketahanan Pangan Indonesia</h1>
        <p class="about-body">
          Satu misi yang menggerakan Wijaya Ayam berdiri.<br />
          Produksi dan distribusi, dua hal yang menjadi tantangan di Indonesia untuk memiliki ketahanan pangan yang kuat.
        </p>
        <p class="about-body">
          Industri ayam broiler merupakan salah satu komponen yang ikut berkontribusi besar dalam menyediakan kebutuhan pangan berprotein tinggi yang terjangkau untuk masyarakat luas.
        </p>
        <p class="about-body">
          Untuk itu Wijaya Ayam hadir membersamai para peternak ayam broiler Indonesia dalam mengoptimalkan hasil ternak, mengolah serta menyediakan rantai distribusi untuk pemerataan pangan ke seluruh wilayah Indonesia.
        </p>
      </div>
      <div class="about-image">
        <img src="../assets/Ayam2.png" alt="Chickin Indonesia" />
      </div>
    </div>
  </section>

    <!-- Section: Tim Kami -->
    <section class="bg-gradient-to-b from-green-50 to-green-100 py-16 px-4">
      <div class="container mx-auto text-center">
        <h2 class="text-4xl font-extrabold text-gray-800 mb-10">Tim Kami</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <!-- Member 1 -->
          <div
            class="p-6 bg-white rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-2 transition duration-300">
            <img src="../assets/bima.jpg" alt="CEO" class="w-24 h-24 rounded-full mx-auto mb-4 shadow-md object-cover" />
            <h3 class="text-xl font-semibold text-gray-800">Bimasakti Cahyo Utomo</h3>
            <p class="text-gray-600">CEO</p>
          </div>
          <!-- Member 2 -->
          <div
            class="p-6 bg-white rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-2 transition duration-300">
            <img src="../assets/maul.png" alt="Head of Operations"
              class="w-24 h-24 rounded-full mx-auto mb-4 shadow-md object-cover" />
            <h3 class="text-xl font-semibold text-gray-800">Maulana Agus Setiawan</h3>
            <p class="text-gray-600">Head of Operations</p>
          </div>
          <!-- Member 3 -->
          <div
            class="p-6 bg-white rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-2 transition duration-300">
            <img src="../assets/ifandi.jpg" alt="Marketing Lead"
              class="w-24 h-24 rounded-full mx-auto mb-4 shadow-md object-cover" />
            <h3 class="text-xl font-semibold text-gray-800">Muhammad Ifandi</h3>
            <p class="text-gray-600">Marketing Lead</p>
          </div>
        </div>
      </div>
    </section>

    <section class="location-section bg-green-100">
    <div class="location-container">
      <div class="location-text">
        <h2 class="location-title text-black">Temukan Lokasi Kami</h2>
        <p class="location-description text-black"> 
          Klik pada peta untuk membuka lokasi kami di Google Maps.
        </p>
      </div>
      <div class="location-map">
        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d428.8959203077844!2d116.97473055333546!3d-0.4237247569681256!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sid!2sid!4v1733880692643!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
    </div>
  </section>
  <Footer/>
  </div>
</template>

<style>
.bg-about {
  background-image: url(../assets/background2.png);
}

.about {
  width: 100%;
  position: absolute;
  top: 0;
}

.about-section {
  background-color: #f9f9f9;
  padding: 50px 20px;
  font-family: Arial, sans-serif;
}

.about-container {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: center;
  gap: 30px;
  max-width: 1200px;
  margin: 0 auto;
}

.about-text {
  flex: 1;
}

.about-title {
  font-size: 28px;
  font-weight: bold;
  margin-bottom: 20px;
}

.about-body {
  font-size: 16px;
  line-height: 1.8;
  margin-bottom: 15px;
  color: #555;
}


.about-image {
  flex: 1;
  display: flex;
  justify-content: center;
}

.about-image img {
  width: 100%;
  max-width: 500px;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.location-section {
  padding: 50px 20px;
  font-family: Arial, sans-serif;
}

.location-container {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: center;
  gap: 30px;
  max-width: 1200px;
  margin: 0 auto;
}

.location-text {
  flex: 1;
}

.location-title {
  font-size: 28px;
  font-weight: bold;
  margin-bottom: 20px;
}

.location-description {
  font-size: 16px;
  line-height: 1.8;
}

.location-map {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
}

.location-map img {
  width: 100%;
  max-width: 600px;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Responsivitas */
@media (max-width: 768px) {
  .about-container {
    flex-direction: column;
    text-align: center;
  }

  .about-image img {
    margin-top: 20px;
  }

  .location-container {
    flex-direction: column;
    text-align: center;
  }

  .location-map img {
    margin-top: 20px;
  }
}
</style>