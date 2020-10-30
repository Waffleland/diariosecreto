<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script type="application/javascript">
  $(".alternarFormularios").click(function(){
      $("#formularioRegistro").toggle();
      $("#formularioLogin").toggle();
  });

  $('#diario').on('input propertychange', function() {

    $.ajax({
        method:"POST",
        url:"actualizarBD.php",
        data:{content: $("#diario").val()}
})
});
</script>
    </body>
</html>