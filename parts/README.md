Use parts/ for any reusable templates that don't encompass a whole page. When using with `get_template_part()` you must include the path, eg. if you want to include parts/entry-single.php, write:

	get_template_part( 'parts/entry', 'single' );