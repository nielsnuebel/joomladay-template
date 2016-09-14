<?php
defined('_JEXEC') or die('Finger weg!');

$app   = JFactory::getApplication();
$doc   = JFactory::getDocument();
$tpath = $this->baseurl . '/templates/' . $this->template;

// Add CSS and Javascript
$doc->addStyleSheet($tpath . '/css/bootstrap.min.css');
$doc->addStyleSheet($tpath . '/css/style.css');
$doc->addScript($tpath . '/js/jquery.min.js');
$doc->addScript($tpath . '/js/tether.min.js');
$doc->addScript($tpath . '/js/bootstrap.min.js');

// Headcleaner
$this->setGenerator(null);

// force latest IE & chrome frame
$doc->setMetadata('x-ua-compatible','IE=edge,chrome=1');
$doc->setMetaData('viewport', 'width=device-width, initial-scale=1, shrink-to-fit=no');

$filesjs = array(
	"media/jui/js/jquery.min.js",
	"media/jui/js/jquery-noconflict.js",
	"media/jui/js/jquery-migrate.min.js",
	"media/system/js/caption.js"
);
$scripts = array();

foreach ($doc->_scripts as $name => $details)
{
	$add = true;

	foreach ($filesjs as $dis)
	{
		if (strpos($name, $dis) !== false)
		{
			$add = false;
			break;
		}
	}

	if ($add)
	{
		$scripts[$name] = $details;
	}
}

$doc->_scripts = $scripts;

$doc->_script['text/javascript'] = str_replace(
	"jQuery(window).on('load',  function() {\n\t\t\t\tnew JCaption('img.caption');\n\t\t\t});",
	'',
	$doc->_script['text/javascript']
);
?>

<!doctype html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
	<head>
		<jdoc:include type="head" />
	</head>
	<body>
	<?php if ($this->countModules('menu')) : ?>
	<nav class="navbar navbar-fixed-top navbar-dark bg-inverse">
		<a class="navbar-brand" href="<?= $this->baseurl; ?>"><?= $app->get('sitename'); ?></a>
		<jdoc:include type="modules" name="menu" />
	</nav>
	<?php endif; ?>
	<div class="container">
		<jdoc:include type="message" />
		<div class="starter-template">
		<jdoc:include type="component" />
		</div>
	</div>
		<jdoc:include type="modules" name="debug" style="none" />
	</body>
</html>
