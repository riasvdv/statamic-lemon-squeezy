<template>
  <fieldset class="flex flex-col gap-2 border-grey-40 rounded m-0 relative">
      <div class="field-inner" v-show="!isLoadingStores && !error && stores.length > 1">
        <label class="publish-field-label">Store</label>
        <select-input v-model="value.store" :options="stores" />
      </div>
      <div class="field-inner">
        <label class="publish-field-label">Product</label>
        <select-input v-show="!isLoadingProducts && products.length > 0" v-model="value.product" :options="products" />
        <div v-show="isLoadingStores">
          <small>Loading stores...</small>
        </div>
        <div v-show="!isLoadingStores && isLoadingProducts">
          <small>Loading your products...</small>
        </div>
        <div v-show="!isLoadingProducts && products.length === 0">
          <small>No products found...</small>
        </div>
      </div>
      <div class="field-inner">
        <label class="publish-field-label">Button text</label>
        <text-input v-model="value.buttonText" />
      </div>
      <div class="field-inner">
        <label class="publish-field-label">Use checkout overlay?</label>
        <div class="flex items-center gap-2">
          <toggle-input v-model="value.overlay" />
          <p class="text-grey text-sm" v-show="value.overlay">Your checkout will be opened in a modal window.</p>
          <p class="text-grey text-sm" v-show="!value.overlay">Your customer will be redirected to your checkout page.</p>
        </div>
      </div>
      <div class="flex gap-2 items-center">
        <label class="publish-field-label">Show store logo</label>
        <toggle-input v-model="value.showStoreLogo" />
      </div>
      <div class="flex gap-2 items-center">
        <label class="publish-field-label">Show product media</label>
        <toggle-input v-model="value.showMedia" />
      </div>
      <div class="flex gap-2 items-center">
        <label class="publish-field-label">Show product description</label>
        <toggle-input v-model="value.showDescription" />
      </div>
      <div class="flex gap-2 items-center">
        <label class="publish-field-label">Show discount code</label>
        <toggle-input v-model="value.showDiscountCode" />
      </div>
      <div v-show="error">
        <small class="text-red">Error: {{ error }}</small>
      </div>
  </fieldset>
</template>

<script>
export default {

  mixins: [Fieldtype],

  data() {
    return {
      error: null,
      stores: [],
      products: [],
      isLoadingStores: true,
      isLoadingProducts: true,
    }
  },

  created() {
      if (! this.value) {
        this.value = {
          store: null,
          product: null,
          buttonText: null,
          overlay: false,
          showStoreLogo: true,
          showMedia: true,
          showDescription: true,
          showDiscountCode: true,
        }
      }

      if (this.value.showStoreLogo === undefined) {
        this.value.showStoreLogo = true;
      }

      if (this.value.showMedia === undefined) {
        this.value.showMedia = true;
      }

      if (this.value.showDescription === undefined) {
        this.value.showDescription = true;
      }

      if (this.value.showDiscountCode === undefined) {
        this.value.showDiscountCode = true;
      }
  },

  mounted() {
    this.isLoadingStores = true;
    fetch(cp_url("/lemon-squeezy/api/stores"))
        .then(response => response.json())
        .then(stores => {
          this.isLoadingStores = false;

          if (stores.error) {
            this.error = stores.error;
            return;
          }

          this.stores = stores;

          if (stores.length) {
            let selectedStoreIndex = stores.findIndex(
                store => store.value === this.value.store
            );

            if (selectedStoreIndex === -1) {
              selectedStoreIndex = 0;
            }

            this.value.store = stores[selectedStoreIndex].value;
            this.getProducts(stores[selectedStoreIndex].value);
          }
        });
  },

  watch: {
    'value.store': function (store) {
      this.value.product = null;
      this.getProducts(store);
    },
    'value': {
      deep: true,
      handler: function (newValue) {
        this.update(newValue);
      }
    }
  },

  methods: {
    getProducts(store_id) {
      this.products = [];
      this.isLoadingProducts = true;

      return fetch(cp_url('/lemon-squeezy/api/products?store_id=' + store_id))
          .then(response => response.json())
          .then(products => {
            if (products.error) {
              this.error = products.error;
              return;
            }

            this.products = products;

            if (products.length) {
              let selectedProductIndex =
                  products.findIndex(
                      product => product.value === this.value.product
                  );

              if (selectedProductIndex === -1) {
                selectedProductIndex = 0;
              }

              this.value.product = products[selectedProductIndex].value;
            }
          })
          .finally(() => {
            this.isLoadingProducts = false;
          });
    }
  }
};
</script>
