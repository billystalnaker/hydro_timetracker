<!DOCTYPE html>
<html>
    <head>
	<?php
	$links = $this->config->item('links');
	if(is_array($links)){
	    foreach($links as $link){
		echo link_tag($link, array('public'=>true));
	    }
	}
	?>
    </head>
    <body>
        <div>
	    <?php
	    $scripts = $this->config->item('scripts');
	    if(is_array($scripts)){
		foreach($scripts as $script){
		    echo script_tag($script);
		}
	    }
	    ?>

        </div>
        <div id="wrapper">
	    <?php
	    echo $this->load->view('tpl/nav', $this->data, true);
	    ?>
            <div id="page-wrapper">
		<?php
		echo $content;
		?>
            </div>
        </div>
    </body>
</html>