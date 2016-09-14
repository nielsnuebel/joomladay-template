<?php
defined('_JEXEC') or die('Finger weg!');

$app   = JFactory::getApplication();
$doc   = JFactory::getDocument();
$tpath = $this->baseurl . '/templates/' . $this->template;

// Getting params from template
$params             = $app->getTemplate(true)->params;
$cssfilename        = $params->get('cssfilename', 'style.css');

// Add CSS and Javascript
$doc->addStyleSheet($tpath . '/css/' . $cssfilename);
$doc->addScript($tpath . '/js/script.js');

// Headcleaner
$this->setGenerator(null);

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
		<jdoc:include type="modules" name="logo" />
		<jdoc:include type="message" />
		<jdoc:include type="component" />
		<jdoc:include type="modules" name="debug" style="none" />
	</body>
</html>
