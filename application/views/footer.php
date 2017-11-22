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

  $(window).scroll(function(){
    if($(this).scrollTop() > 200){
      $('a.alCielo').stop().fadeIn('fast');
    }else{
      $('a.alCielo').stop().fadeOut('fast');
    }
  });

})

</script>







<style>
#info {
  display: none;
  border-radius: 6px;
  opacity: 0.3;
  right: 0;
  bottom: 0; position: fixed; width: 400px; background: rgb(232, 232, 232); margin: 7px; font-size: 11px; padding: 10px;
  z-index: 999; overflow-y: auto; max-height: 300px;
  color: rgb(27, 66, 131);
  transition: all .2s
}
#info:hover {  opacity: .9; }
</style>
<div id="info">
  <pre>
    <?php
    print_r($this->session->userdata());
    ?>
  </pre>
</div>
