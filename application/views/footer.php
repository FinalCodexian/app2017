
</body>
</html>


<script src="<?=base_url('tools/jquery.mobile.min.js');?>"></script>
<script src="<?=base_url('tools/jquery.touchSwipe.min.js');?>"></script>


<script type="text/javascript">
$(function(){

  if($.browser.mobile){
    console.log("mobile");
  };
  // swal('Hello world!','', 'success')

  //Enable swiping...
  $(document).swipe( {
    swipeLeft:function(event, distance, duration, fingerCount, fingerData, currentDirection) {
      // Cerrar menu
      $(".hamburger").removeClass("is-active");
      $("#wrapper").stop().animate({ paddingLeft: 0}, 150, function(){
        $('#example').DataTable().columns.adjust().draw();
        $("#sidebar").stop().animate({"width": "0"}, 100);
      });

    },
    swipeRight:function(event, distance, duration, fingerCount, fingerData, currentDirection){
      // Abrir menu
      $(".hamburger").addClass("is-active");
      $("#sidebar").stop().animate({width: 220}, 150, function(){
        $("#wrapper").stop().animate({ paddingLeft: 220}, 100)
        $("#top-menu").stop().animate({"width": "100%",marginLeft: 0}, 100, function(){
          $('#example').DataTable().columns.adjust().draw();
        });
      });

    },
    threshold: 150
  });

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
