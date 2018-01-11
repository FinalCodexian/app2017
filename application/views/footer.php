

</body>
</html>

<script type="text/javascript">
$(function(){

  // swal('Hello world!','', 'success')

  if(jQuery.browser.mobile){


    $(document).swipe({
      swipeStatus:function(event, phase, direction, distance, duration, fingers)
      {
        if (phase=="move" && direction =="right") {
          // Abrir menu
          $(".hamburger").addClass("is-active");
          $("#sidebar").stop().animate({width: 220}, 150, function(){
            $("#wrapper").stop().animate({ paddingLeft: 220}, 100)
            $("#top-menu").stop().animate({"width": "100%",marginLeft: 0}, 100, function(){
              $('#example').DataTable().columns.adjust().draw();
            });
          });
          return false;
        }
        if (phase=="move" && direction =="left") {
          // Cerrar menu
          $(".container").addClass("open-sidebar");
          $(".hamburger").removeClass("is-active");
          $("#wrapper").stop().animate({ paddingLeft: 0}, 150, function(){
            $('#example').DataTable().columns.adjust().draw();
            $("#sidebar").stop().animate({"width": "0"}, 100);
          });

          return false;
        }
      }
    });
    console.log('You are using a mobile device!');
  }
  


  $(document).bind('keydown.f1', function(e){
    e.preventDefault();
    $(".xInfoBox").stop().slideToggle();
  });

  $(".xhelp").on("click", function(e){
    e.preventDefault();
    $(".xInfoBox").stop().slideToggle();
  })

  $(".mnuOpciones").dropdown();

  $(".mobile-button").click(function(e){
    e.preventDefault();
    if($(".hamburger").hasClass('is-active')){
      // Cerrar menu
      $(".hamburger").removeClass("is-active");
      $("#wrapper").stop().animate({ paddingLeft: 0}, 150, function(){
        $('#example').DataTable().columns.adjust().draw();
        $("#sidebar").stop().animate({"width": "0"}, 100);
      });

    }else{
      // Abrir menu
      $(".hamburger").addClass("is-active");
      $("#sidebar").stop().animate({width: 220}, 150, function(){
        $("#wrapper").stop().animate({ paddingLeft: 220}, 100)
        $("#top-menu").stop().animate({"width": "100%",marginLeft: 0}, 100, function(){
          $('#example').DataTable().columns.adjust().draw();
        });
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
