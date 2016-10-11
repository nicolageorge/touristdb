<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url();?>public/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo base_url();?>public/css/admin.css" rel="stylesheet">
    <link href="<?php echo base_url();?>public/css/backgrid.min.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="<?php echo base_url();?>public/js/lib/jquery.min.js"></script>

  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Turistii veseli</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="<?php echo base_url(); ?>index.php/sights/">Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>index.php/sights/new">Adauga</a></li>
          </ul>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <li><a href="<?php echo base_url(); ?>index.php/sights/">Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>index.php/sights/new">Adauga</a></li>
          </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Dashboard</h1>
          <h2 class="sub-header">Atractii</h2>

          <div class="row">
            <form method="POST" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>">
              <?php 
              echo $sights_filter_form_elements; 
              ?>
              <select name="sightsRegionFilter">
                <option value="">Toate Judetele</option>
                <?php foreach( $regions as $key=>$val ) {
                  echo "<option value='".$key."' ";
                  if( $key == $filter_region_value ) echo " SELECTED ";
                  echo ">". $val ."</option>"; 
                }?>
              </select>
              &nbsp;&nbsp;&nbsp;
              Validat <?php var_dump( $filter_validated_value );?>
              <select name="sightsValidatedFilter">
                <option value="">Toate</option>
                <option value="1" <?php if( $filter_validated_value === "1" ) echo "SELECTED"; ?> >Da</option>
                <option value="0" <?php if( $filter_validated_value === "0" ) echo "SELECTED"; ?> >Nu</option>
              <select>
              <input type="submit" value="cauta" name="submit" >
            </form>
          </div>

          <div class="row">
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Localitate</th>
                  <th>Judet</th>
			         	  <th>Validat</th>
                  <th>Vezi coordonate harta</th>
                  <th>Vezi Adresa pe harta</th>
                  <th>Edit</th>
                  <th>Delete</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach( $sights as $sight ){ ?>
                <tr>
                  <td><?php echo $sight['id'];?></td>
                  <td><?php echo $sight['name'];?></td>
                  <td><?php echo $sight['loc_name'];?></td>
                  <td><?php echo $sight['region'];?></td>
				          <td>
				  	        <?php if( $sight['validated'] === 1) { ?>
				  		        <span aria-hidden="true" class="glyphicon glyphicon-ok green" style="color:green;"></span>
					          <?php }else{ ?>
				  		        <span aria-hidden="true" class="glyphicon glyphicon-remove red"></span>
				  	        <?php } ?>
				          </td> 
                  <td><a target="_blank" href="https://www.google.ro/maps/@<?php if( isset( $sight['latitude'] ) ) echo $sight['latitude'];?>,<?php if( isset( $sight['longitude'] ) )echo $sight['longitude'];?>,11z?hl=ro">Vezi pe harta</a></td>
                  <td><a target="_blank" href="http://maps.google.com/?q=<?php echo $sight['address'];?>" >Vezi Adresa pe harta</a>
                  <td><a href="<?php echo base_url();?>index.php/sights/edit/<?php echo $sight['id'];?>">Edit</a></td>
                  <td><a href="<?php echo base_url();?>index.php/sights/delete/<?php echo $sight['id'];?>" onclick="return confirm('Sunteti sigur ca doriti sa stergeti?')">Delete</a></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
            <div id="pagination">
              <?php echo $this->pagination->create_links(); ?>
            </div>
      </div>
    </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo base_url();?>public/js/lib/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>public/js/lib/docs.min.js"></script>

  </body>
</html>
