<template>
 <div class="content-header no-print bg-info">
 <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">{{ $route.meta.title }}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><router-link :to="{ name: 'home' }"> Home  </router-link></li>
               <li class="breadcrumb-item">{{ $route.meta.title }}</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
  </div>
</template>

     

<script>
export default {
    name : 'breadcrumbKomponen'
}
</script>