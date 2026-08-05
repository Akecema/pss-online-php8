<?php
/**
 * test_kalendar.php
 * Part of: Core / entry-point script
 * Filename suggests: test kalendar
 *
 * Behavior: no form submission, file upload, or export detected (likely a display/listing page, utility, or bootstrap/include file).
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
?>
<!doctype html>
<html>
  <head>
    <title>Date</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
    <link rel="stylesheet" href="themes/readable/bootstrap.css">
    <link rel="stylesheet" href="css/datepicker.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
  </head>

  <body>
    <div class="container">
      <div class="well">
        <h4>Date Picker</h4>
    <div class="input-append date" id="dp2" data-date="15-07-2013" data-date-format="dd-mm-yyyy">
      <input class="span2" size="16" type="text" value="15-07-2013">
      <label class="add-on"><i class="icon-calendar"></i></label>
    </div>
      </div>
    </div>
    <script>

      $(function(){

      $('#dp1').datepicker();
      $('#dp2').datepicker();

      });
    </script>
  </body>
</html>