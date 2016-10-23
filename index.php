<?php
require 'vendor/autoload.php';

use Tech\Resizer;
use Tech\FileNotFoundException;


if ($_SERVER['REQUEST_METHOD'] == 'POST')
{

    $settings['watermark'] = intval(sanitize($_POST['watermark']));

    $settings = $_POST;

    $images = $_FILES;


    try
    {
        $resizer = new Resizer;

        echo $response = $resizer->batchResize($images, $settings);
        die();

    }
    catch(FileNotFoundException $e)
    {

    }
}


?>

<!doctype html>
<html lang="en-GB">
<head>
    <meta charset="UTF-8">
    <title>Batch Resizer Script</title>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootswatch/3.3.4/lumen/bootstrap.min.css"/>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"/>

    <link rel="stylesheet" href="assets/css/app.css"/>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="assets/js/app.js"></script>
</head>
<body <?php echo (! isset($_GET['cantdownload'])) ? '' : ' onload="TechResizer.RenderAlert(\'warning\',\'Cannot download a file that does not exist!!!\', \'Error!\');" ';?> >
    <div class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <a href="../" class="navbar-brand">Image Resizer Script</a>
                <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="navbar-collapse collapse" id="navbar-main">
            </div>
        </div>
    </div>


    <div class="container">

        <div class="page-header" id="banner">
            <div class="row">
                <div class="col-lg-8 col-md-7 col-sm-6">
                    <h1 class="pad20"><i class="fa fa-image text-success"></i> Batch Resizer</h1>
                    <p class="lead">Batch Resizing Images Perfectly inspired by Wunmi Goerge <i class="fa fa-smile-o text-warning"></i>&hellip;</p>
                </div>

            </div>
        </div>

        <div id="alertBox">

        </div>

        <!-- Buttons
        ================================================== -->
        <div class="bs-docs-section">

            <div class="row">
                <div class="well">
                    <h3>
                        Usage:
                        <br/>
                        <small>
                            To Batch Resize Images, Just Drag a folder into the Drop Zone. A list of files would be shown
                            on the left with unsupported files striked out. Fill the Output Settings,
                            Click the Process Button and wait to get a zip file filled with the resized Images.
                        </small>
                    </h3>
                </div>
            </div>

            <div class="row">


                <div class="col-lg-6">
                    <div class="page-header">
                        <h3><small>Drag and drop a directory / folder</small></h3>
                    </div>

                    <form enctype="multipart/form-data" method="post" action="index.php">
                        <div id="fileHolder">
                            <div class="folder-icon"></div>
                            <input name="images[]" type="file" multiple webkitdirectory  id="fileUrl"/>

                        </div>

                        <div class="well">
                            <div class="form-horizontal">
                                <fieldset>
                                    <legend class="text-center">Settings For Output <br/><small><span class="text-danger">*</span> means required.</small></legend>

                                    <div class="form-group">

                                        <label for="imageSizes" class="col-lg-3 control-label"><span class="text-danger">*</span> Select Sizes:</label>
                                        <div class="col-lg-9">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" onchange="TechResizer.ToggleInputWithClass('sm')" name="imageSizes[]" value="sm"> Small (Default Size W: 200px H: 210px )
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" onchange="TechResizer.ToggleInputWithClass('md')" name="imageSizes[]" value="md"> Medium (Default Size W: 200px H: 210px )
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" onchange="TechResizer.ToggleInputWithClass('lg')" name="imageSizes[]" value="lg"> Large (Default Size W: 200px H: 210px )
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="quality" class="col-lg-3 control-label">Width:</label>
                                       <div class="col-lg-5">
                                           <div class="input-group col-lg-12">
                                               <div class="input-group-addon">Small</div>
                                               <input type="number" class="sm form-control" name="width[sm]" placeholder="80" disabled>
                                               <div class="input-group-addon">px</div>
                                           </div>
                                           <br/>
                                           <div class="input-group col-lg-12">
                                               <div class="input-group-addon">Medium</div>
                                               <input type="number"  class="md form-control" name="width[md]" placeholder="80" disabled>
                                               <div class="input-group-addon">px</div>
                                           </div>
                                           <br/>
                                           <div class="input-group col-lg-12">
                                               <div class="input-group-addon">Large</div>
                                               <input type="number"  class="lg form-control" name="width[lg]" placeholder="80" disabled>
                                               <div class="input-group-addon">px</div>
                                           </div>
                                       </div>

                                        <!--                                    <span class="help-block">A longer line.</span>-->
                                    </div>

                                    <div class="form-group">
                                        <label for="quality" class="col-lg-3 control-label">Height:</label>
                                        <div class="col-lg-5">
                                            <div class="input-group col-lg-12">
                                                <div class="input-group-addon">Small</div>
                                                <input type="number"  class="sm form-control" name="height[sm]" placeholder="80" disabled>
                                                <div class="input-group-addon">px</div>
                                            </div>
                                            <br/>
                                            <div class="input-group col-lg-12">
                                                <div class="input-group-addon">Medium</div>
                                                <input type="number"  class="md form-control" name="height[md]" placeholder="80" disabled>
                                                <div class="input-group-addon">px</div>
                                            </div>
                                            <br/>
                                            <div class="input-group col-lg-12">
                                                <div class="input-group-addon">Large</div>
                                                <input type="number"  class="lg form-control" name="height[lg]" placeholder="80" disabled>
                                                <div class="input-group-addon">px</div>
                                            </div>
                                        </div>
    <!--                                    <span class="help-block">A longer line.</span>-->

                                    </div>

                                    <div class="form-group">
                                        <label for="quality" class="col-lg-3 control-label">Quality:</label>
                                        <div class="input-group col-lg-3">
                                            <input type="number" min="30" max="100" class="form-control" name="quality" placeholder="80">
                                            <div class="input-group-addon">%</div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-3 control-label">Watermark:</label>
                                        <div class="col-lg-9">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="watermark" id="optionsRadios1" value="1" checked="">
                                                    Yes, Watermark the Images Please
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="watermark" id="optionsRadios2" value="0">
                                                    No, I don't Need A Watermark
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-lg-10 col-lg-offset-3">
                                            <button type="submit" id="submit" class="btn btn-primary"> <i class="fa fa-spinner"></i> Batch Process</button>
                                        </div>
                                    </div>
                                </fieldset>

                            </div>
                        </div>

                    </form>

                </div>


                <div class="col-lg-offset-1 col-lg-5">
                    <div class="page-header">
                        <h3><small>File Uploads</small></h3>

                    </div>

                    <ul id="fileOutput">
                        <h2 class="pad100"><i class="fa fa-folder-open"></i> No Folder Dropped Yet!!</h2>
                        <h3><small>Drag A folder to the Drop Zone and all Files would be listed Here, Files That aren't Images would be striked out.</small></h3>

                    </ul>
                </div>
            </div>
        </div>

        <footer>
            <div class="row">
                <div class="col-lg-12">

                    <ul class="list-unstyled">
                        <li class="pad100 pull-right"><a href="#top"><i class="fa fa-arrow-up text-danger"></i> Back to top</a></li>
                        <li><a href="http://dami.ogunmoye.com/blog" target="_blank">Blog</a></li>
                        <li><a href="https://twitter.com/doshandle" target="_blank"><i class="fa fa-twitter"></i>  Twitter: @DOsHandle</a></li>
                        <li><a href="https://github.com/dammyammy/batch-resizer" target="_blank"><i class="fa fa-github text-black"> </i> GitHub: dammyammy</a></li>
                    </ul>
                    <p>Created by <a href="http://dami.ogunmoye.com" rel="nofollow" target="_blank">Dami Ogunmoye</a>. Contact him at dami[at]igunmoye[dot]com
                    <p>Code released under the <a href="https://github.com/dammyammy/batch-resizer/blob/gh-pages/LICENSE" target="_blank">MIT License</a>.</p>
                    <p>
                        Image Manupulation Handled Using <a href="http://image.intervention.io" target="_blank" rel="nofollow">Intervention Image</a> |
                        Theme from <a href="http://bootstrapcdn.com" rel="nofollow">Bootstrap CDN</a> |
                        Icons from <a href="http://fontawesome.io" rel="nofollow">Font Awesome</a> |
                        Web fonts from <a href="http://www.google.com/webfonts" rel="nofollow">Google</a>.
                    </p>

                </div>
            </div>

        </footer>


    </div>



    <script>
        (function(){

            TechResizer.PickAFolder();

            $('form button#submit').on('click', function(e)
            {
                e.preventDefault();

                var clicked = this;
                var form = $('form');
                var spinner = 'submit';

                TechResizer.RenderSpinner(spinner);

                TechResizer.ajaxRequest(form,spinner);

            });

        })();
    </script>
</body>
</html>
