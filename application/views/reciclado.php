<?php // TODO: combo dropdown minibusqueda  ?>

<style>
#cboMarca .search.icon { top: 8px !important; color: green}
#cboMarca .remove.icon { top: 8px !important; color: red}
</style>

<script>
$('#cboMarca').dropdown({
  minCharacters: 3,
  forceSelection:false,
  apiSettings: {
    url: '<?=site_url('datos/fnListaSimple_Clientes');?>?q={query}',
    data: {query: ''},
    cache:false
  },
  saveRemoteData:false,
  filterRemoteData: false,
  onChange: function(value, text){
    console.log(value);

    $('#cboMarca .icon').addClass("remove")
    .on('click', function(e){
      $(this).parent('.dropdown').dropdown('clear');
      $('#cboMarca .icon').removeClass("remove");
      $('#cboMarca .icon').addClass("search");
      e.stopPropagation();
    });


  }
});

</script>
