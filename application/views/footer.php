</body>
</html>

<script type="text/javascript">
$(function(){
  $(".mnuOpciones").dropdown();

  $(".mobile-button").click(function(e){
    e.preventDefault();
    if($(".hamburger").hasClass('is-active')){
      // Cerrar menu
      $(".hamburger").removeClass("is-active");
      $("#wrapper").stop().animate({ paddingLeft: 0}, 150, function(){
        $("#sidebar").stop().animate({"width": "0"}, 100);
      });
    }else{
      // Abrir menu
      $(".hamburger").addClass("is-active");
      $("#sidebar").stop().animate({width: 220}, 150, function(){
        $("#wrapper").stop().animate({ paddingLeft: 220}, 100)
        $("#top-menu").stop().animate({"width": "100%",marginLeft: 0}, 100);
      });
    }
  });
})
</script>


<style>
#info {
  border-radius: 6px;
  opacity: 0;
  bottom: 0; position: fixed; width: 360px; background: rgb(232, 232, 232); margin: 7px; font-size: 11px; padding: 10px;
  z-index: 999; overflow-y: auto; display: inline-block; max-height: 400px;
  color: rgb(27, 66, 131)
}
#info:hover {  opacity: .9; }
</style>
<div id="info">
  Session:
  <pre>
    <?php
    print_r($this->session->userdata());
    ?>
  </pre>
</div>
