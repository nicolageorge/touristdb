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
          <a class="navbar-brand" href="<?php echo base_url(); ?>index.php/sights/">Turistii veseli</a>
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

          <h1 class="page-header">Adauga atractie</h1>

          <h2><?php echo $message;?></h2>

          <?php $attributes = array("class" => "form-horizontal", "id" => "sightForm", "name" => "sightform");
          echo form_open("index.php/sights/edit/".$currentSight[ "id" ], $attributes); ?>

            <fieldset class="form-group">
              <label for="sightNameInput">Nume</label>
              <input type="text" class="form-control" id="sightNameInput" placeholder="Numele atractiei" name="sightName" value="<?php echo $sightName; ?>">
              <?php if( form_error('sightNameValidation') != "" ){ ;?>
                <small class="text-muted alert"><?php echo form_error('sightNameValidation'); ?></small>
              <?php } ?>
            </fieldset>

            <fieldset class="form-group">
              <label for="sightDescriptionInput">Descriere</label>
              <textarea class="form-control" id="sightDescriptionInput" placeholder="Descrierea atractiei" name="sightDescription" rows="4"><?php echo $sightDescription;?></textarea>
              <?php if( form_error('sightDescriptionValidation') != "" ){ ;?>
                <small class="text-muted alert"><?php echo form_error('sightDescriptionValidation'); ?></small>
              <?php } ?>
            </fieldset>

            <fieldset class="form-group">
              <label for="sightCategoryInput">Categorie</label>
              <select class="form-control" id="sightCategoryInput" name="sightCategory">
                <?php foreach( $sightCategories as $key=>$value ){ ?>
                  <option value="<?php echo $value["id"];?>" ><?php echo $value["name"];?></option>
                <?php } ?>
              </select>
              <?php if( form_error('sightCategoryValidation') != "" ){ ;?>
                <small class="text-muted alert"><?php echo form_error('sightCategoryValidation'); ?></small>
              <?php } ?>
            </fieldset>

            <fieldset class="form-group">
              <label for="sightSubcategoryInput">Subcategorie</label>
              <select class="form-control" id="sightSubcategoryInput" name="sightSubcategory">
                <?php foreach( $sightSubcategories as $key=>$value ){ ?>
                  <option value="<?php echo $value["id"];?>" ><?php echo $value["name"];?></option>
                <?php } ?>
              </select>
              <?php if( form_error('sightLocalityValidation') != "" ){ ;?>
                <small class="text-muted alert"><?php echo form_error('sightLocalityValidation'); ?></small>
              <?php } ?>
            </fieldset>

            <fieldset class="form-group">
              <label for="sightLocalityInput">Localitate</label>
              <select class="form-control" id="sightLocalityInput" name="sightLocality">
                <?php foreach( $regionLocalities as $key=>$value ){ ?>
                  <option value="<?php echo $value["id"];?>" ><?php echo $value["name"];?></option>
                <?php } ?>
              </select>
              <?php if( form_error('sightLocalityValidation') != "" ){ ;?>
                <small class="text-muted alert"><?php echo form_error('sightLocalityValidation'); ?></small>
              <?php } ?>
            </fieldset>

            <button type="submit" name="sightAdd" value="sightAdd" class="btn btn-primary">Salveaza</button>

            <?php echo form_close(); ?>
            <?php echo $this->session->flashdata('msg'); ?>
        
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
