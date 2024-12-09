<script setup>
import { ref } from 'vue';
import { useForm, Link, router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const props = defineProps({
    isEdit: Boolean,
    product: Object,
});

const form = useForm({
    nama_produk: props.isEdit ? props.product.nama_produk : '',
    deskripsi: props.isEdit ? props.product.deskripsi : '',
    harga: props.isEdit ? props.product.harga : 0,
    stok: props.isEdit ? props.product.stok : 0,
    gambar: null,
});

const preview_image = ref(null);
if (props.isEdit && props.product.gambar) { // ngambil data gambar
    preview_image.value = `/storage/${props.product.gambar}`;
    // preview_image.value = `/storage/` + form.gambar; // Alternatif
}

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        if (file.size > 2048 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 2 MB.');
            return;
        }
        if (!file.type.startsWith('image/')) {
            alert('File yang diupload bukan gambar.');
            return;
        }
        preview_image.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    URL.revokeObjectURL(preview_image.value);
    if (props.isEdit) { 
        // Kenapa pake punya props ? soalnya di form gak ada id nya
        // Kenapa pake router ? biar bisa exclude field

        // --> laravel gak bisa pake PUT atau PATCH buat update data
        // maka dari itu taro PATCH nya di _method, ini namanya 'Spoofing'
        // ini dari awal rilis laravel masih belum dibaiki
        if (form.gambar instanceof File) {
            router.post(`/dashboard/product/update/${props.product.id_produk}`, {
                _method: 'patch',
                nama_produk: form.nama_produk,
                deskripsi: form.deskripsi,
                harga: form.harga,
                stok: form.stok,
                gambar: form.gambar,
                onSuccess: () => notifSuccess('Produk berhasil diubah'),
            });
        }else {
            router.post(`/dashboard/product/update/${props.product.id_produk}`, {
                _method: 'patch',
                nama_produk: form.nama_produk,
                deskripsi: form.deskripsi,
                harga: form.harga,
                stok: form.stok,
                onSuccess: () => notifSuccess('Produk berhasil diubah'),
            });
        }

        // Alternatif
        // form.patch(`/dashboard/update/${props.product.id_produk}`, {
        //     onSuccess: () => alert('Product updated successfully'),
        // });
    } else {
        form.post('/dashboard/product/store', {
            onSuccess: () => notifSuccess('Produk berhasil ditambah'),
        });
    }

    // Alternatif
    //contoh  : pake named route
    // form.post(route('dashboard.product.update', {id: props.product.id_produk}), {
    //         onSuccess: () => alert('Product updated successfully'),
    //     });
};

// Alternatif, nambah instance form di sebelah route,
// const submit = () => {
//     URL.revokeObjectURL(preview_image.value);
//     if (props.isEdit) { // Kenapa pake punya props ? soalnya di form gak ada id nya
//         form.post(`/dashboard/update/${props.product.id_produk}`,form, {
//             onSuccess: () => alert('Product updated successfully'),
//         });
//     } else {
//         form.post('/dashboard/store',form, {
//             onSuccess: () => alert('Product created successfully'),
//         });
//     }
// };
</script>


<template>
    <div class="max-w-md mx-auto p-6 bg-white rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">{{ isEdit ? 'Edit Product' : 'Create Product' }}</h1>
        <form @submit.prevent="submit">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                <input type="text" v-model="form.nama_produk"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-indigo-500 focus:ring-indigo-500"
                    required />
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <input type="text" v-model="form.deskripsi"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-indigo-500 focus:ring-indigo-500"
                    required />
            </div>
            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-gray-700">Harga</label>
                <input type="number" v-model.number="form.harga"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-indigo-500 focus:ring-indigo-500"
                    required min="0" />
            </div>
            <div class="mb-4">
                <label for="stock" class="block text-sm font-medium text-gray-700">Stok</label>
                <input type="number" v-model.number="form.stok"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-indigo-500 focus:ring-indigo-500"
                    required min="0" />
            </div>
            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700">Gambar Produk:</label>
                <input type="file" id="image" @change="handleImageUpload" @input="form.gambar = $event.target.files[0]" accept="image/*"
                    class="file-input file-input-bordered w-full" />

                <img v-if="preview_image" :src="preview_image" alt="Preview Gambar"
                    class="mt-2 w-full h-auto rounded-md" />
            </div>
            <div class="flex justify-between">
                <button type="submit"
                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ isEdit ? 'Update Product' : 'Create Product' }}
                </button>
                <Link :href="route('dashboard.product')">
                    <button type="button"
                        class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Batal
                    </button>
                </Link>
            </div>
        </form>
    </div>
</template>

<script>
function notifSuccess (text) {
    Swal.fire({
        title: text,
        icon: 'success',
    })
}
</script>