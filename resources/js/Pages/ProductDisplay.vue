<script setup>
import ProductCard from "@/Components/ProductCard.vue";
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';
import { usePage } from '@inertiajs/vue3';
const { products } = usePage().props;
</script>

<template>
  <Header/>
  <section class="py-28 px-16 bg-slate-50">
    <div class="py-4 space-y-4"> <!-- Gak pake Grid karena udah ada flex-wrap dibawah -->
      <h1 class="text-2xl text-center font-bold">Produk Kami</h1>
      <div class="flex flex-wrap justify-center gap-10">
        <ProductCard
          v-for="product in products"
          :key="product.id_produk"
          :product="product"
          @add-to-cart="addToCart"
        />
      </div>
    </div>
  </section>
  <Footer/>
</template>

<!-- <style scoped>
.product-display {
  position: absolute;
  width: 100%;
  height: auto;
  background-color: #c5c5c5;
}

.kotak {
  width: calc(100% - 40px);
  min-height: 88%;
  background-color: white;
  border-radius: 15px;
  margin: 90px auto;
  padding: 40px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.section-title {
  font-size: 24px;
  font-weight: 700;
  text-align: center;
  margin-bottom: 24px;
  color: black;
}


/* Responsivitas */
@media (max-width: 768px) {
  .kotak {
    padding: 20px;
  }

  .section-title {
    font-size: 20px;
    margin-bottom: 16px;
  }
}
</style> -->
