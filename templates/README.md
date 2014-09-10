Use the templates/ folder for all your custom Page templates. Don't put any other templates here, WordPress won't find them.

NOTE: When referring to templates in eg. Custom Meta Boxes, you need to include the path, like this

	'show_on' => array( 'page-template' => 'templates/my-custom-template.php' );